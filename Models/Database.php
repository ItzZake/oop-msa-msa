<?php

class Database
{
    private static $instance;
    private $connection;
    private $host = 'localhost';
    private $dbname = 'wellucation 2';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';

    private function __construct()
    {
        $this->connect();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($sql, $params = [])
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            return false;
        }

        if (!$stmt->execute($params)) {
            return false;
        }

        return $stmt;
    }

    public function connect()
    {
        if ($this->isConnected()) {
            return;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed: ' . $exception->getMessage());
        }
    }

    public function disconnect()
    {
        $this->connection = null;
    }

    public function isConnected()
    {
        return $this->connection instanceof PDO;
    }

    public function beginTransaction()
    {
        return $this->connection && $this->connection->beginTransaction();
    }

    public function commit()
    {
        return $this->connection && $this->connection->commit();
    }

    public function rollBack()
    {
        return $this->connection && $this->connection->rollBack();
    }

    public function lastInsertId()
    {
        return $this->connection ? $this->connection->lastInsertId() : null;
    }

    public function inTransaction()
    {
        return $this->connection && $this->connection->inTransaction();
    }

    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return [];
        }

        return $stmt->fetchAll();
    }

    public function fetchOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return null;
        }

        return $stmt->fetch();
    }

    public function insertGetId($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return false;
        }

        return $this->lastInsertId();
    }

    public function addUser(array $data)
    {
        $this->beginTransaction();

        try {
            $role = $data['role'] ?? '';
            $mappedRole = $role === 'Student' ? 'Child' : $role;
            $email = $data['email'] ?? '';
            $name = trim($data['name'] ?? '');
            $nameParts = preg_split('/\s+/', $name, 2, PREG_SPLIT_NO_EMPTY);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';
            $preferredLanguage = 'EN';
            $passwordHash = password_hash('Welcome@123', PASSWORD_DEFAULT);

            $existingUser = $this->fetchOne('SELECT userID, Role FROM `User` WHERE email = ? LIMIT 1', [$email]);
        if ($existingUser) {
            throw new RuntimeException('A user with this email already exists.');
        }

        $userSql = 'INSERT INTO `User` (`email`, `passwordHash`, `firstname`, `Lastname`, `Role`, `preferredLanguage`, `createdAt`, `isActive`) VALUES (?, ?, ?, ?, ?, ?, NOW(), 1)';
        $userId = $this->insertGetId($userSql, [$email, $passwordHash, $firstName, $lastName, $mappedRole, $preferredLanguage]);
        if ($userId === false) {
            throw new RuntimeException('Failed to create user account.');
        }

        if ($mappedRole === 'Admin') {
            $adminSql = 'INSERT INTO `Admin` (`userID`) VALUES (?)';
            if ($this->query($adminSql, [$userId]) === false) {
                throw new RuntimeException('Failed to create admin profile.');
            }
        } elseif ($mappedRole === 'Teacher') {
            $teacherSql = 'INSERT INTO `Teacher` (`userID`, `phone`, `qualifications`, `specialization`) VALUES (?, ?, ?, ?)';
            if ($this->query($teacherSql, [$userId, $data['teacher_phone'] ?? '', $data['qualifications'] ?? '', $data['specialization'] ?? '']) === false) {
                throw new RuntimeException('Failed to create teacher profile.');
            }
        } elseif ($mappedRole === 'Parent') {
            $parentSql = 'INSERT INTO `Parent` (`userID`, `phone`, `address`) VALUES (?, ?, ?)';
            if ($this->query($parentSql, [$userId, $data['parent_profile_phone'] ?? '', $data['parent_profile_address'] ?? '']) === false) {
                throw new RuntimeException('Failed to create parent profile.');
            }
        } elseif ($mappedRole === 'Child') {
            $parentEmail = trim($data['parent_email'] ?? '');
            $parentName = trim($data['parent_name'] ?? '');
            $parentPhone = $data['parent_phone'] ?? '';
            $parentAddress = $data['parent_address'] ?? '';

            $parentUser = $this->fetchOne('SELECT userID, Role FROM `User` WHERE email = ? LIMIT 1', [$parentEmail]);
            if ($parentUser && $parentUser['Role'] !== 'Parent') {
                throw new RuntimeException('The parent email is already taken by a non-parent account.');
            }

            if (!$parentUser) {
                $parentNameParts = preg_split('/\s+/', $parentName, 2, PREG_SPLIT_NO_EMPTY);
                $parentFirstName = $parentNameParts[0] ?? '';
                $parentLastName = $parentNameParts[1] ?? '';
                $parentPasswordHash = password_hash('Welcome@123', PASSWORD_DEFAULT);
                $parentUserId = $this->insertGetId('INSERT INTO `User` (`email`, `passwordHash`, `firstname`, `Lastname`, `Role`, `preferredLanguage`, `createdAt`, `isActive`) VALUES (?, ?, ?, ?, ?, ?, NOW(), 1)', [$parentEmail, $parentPasswordHash, $parentFirstName, $parentLastName, 'Parent', $preferredLanguage]);
                if ($parentUserId === false) {
                    throw new RuntimeException('Failed to create parent user account.');
                }
                $parentProfileSql = 'INSERT INTO `Parent` (`userID`, `phone`, `address`) VALUES (?, ?, ?)';
                if ($this->query($parentProfileSql, [$parentUserId, $parentPhone, $parentAddress]) === false) {
                    throw new RuntimeException('Failed to create parent profile for student.');
                }
                $parentId = $this->lastInsertId();
            } else {
                $parentUserId = $parentUser['userID'];
                $parentIdResult = $this->fetchOne('SELECT parentID FROM `Parent` WHERE userID = ? LIMIT 1', [$parentUserId]);
                if ($parentIdResult) {
                    $parentId = $parentIdResult['parentID'];
                } else {
                    $parentProfileSql = 'INSERT INTO `Parent` (`userID`, `phone`, `address`) VALUES (?, ?, ?)';
                    if ($this->query($parentProfileSql, [$parentUserId, $parentPhone, $parentAddress]) === false) {
                        throw new RuntimeException('Failed to create parent profile for existing parent user.');
                    }
                    $parentId = $this->lastInsertId();
                }
            }

            if (!$parentId) {
                throw new RuntimeException('Unable to resolve parent profile ID for the child.');
            }

            $medicalNotes = trim((!empty($data['class']) ? 'Class: ' . $data['class'] . '. ' : '') . ($data['allergies'] ?? ''));
            $childSql = 'INSERT INTO `Child` (`parentID`, `name`, `dateOfBirth`, `gender`, `allergies`, `medicalNotes`, `emergencyContact`, `enrollmentStatus`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            if ($this->query($childSql, [$parentId, $name, $data['dob'] ?? '', $data['gender'] ?? '', $data['allergies'] ?? '', $medicalNotes, $data['emergency_contact'] ?? '', 'Pending']) === false) {
                throw new RuntimeException('Failed to create child profile.');
            }
        }

        $this->commit();
        return $userId;
    } catch (Exception $e) {
        if ($this->inTransaction()) {
            $this->rollBack();
        }
        throw $e;
    }
}

    public function updateUser(int $userId, array $data)
    {
        $this->beginTransaction();
        try {
            $fields = [];
            $params = [];
            if (isset($data['email'])) { $fields[] = '`email` = ?'; $params[] = $data['email']; }
            if (isset($data['firstname'])) { $fields[] = '`firstname` = ?'; $params[] = $data['firstname']; }
            if (isset($data['Lastname'])) { $fields[] = '`Lastname` = ?'; $params[] = $data['Lastname']; }
            if (isset($data['Role'])) { $fields[] = '`Role` = ?'; $params[] = $data['Role']; }

            if (!empty($fields)) {
                $params[] = $userId;
                $sql = 'UPDATE `User` SET ' . implode(', ', $fields) . ' WHERE userID = ?';
                if ($this->query($sql, $params) === false) {
                    throw new RuntimeException('Failed to update user.');
                }
            }

            // Update profile tables based on role-specific fields
            $role = $data['Role'] ?? null;
            if ($role === 'Teacher') {
                $sql = 'UPDATE `Teacher` SET phone = ?, qualifications = ?, specialization = ? WHERE userID = ?';
                if ($this->query($sql, [$data['teacher_phone'] ?? '', $data['qualifications'] ?? '', $data['specialization'] ?? '', $userId]) === false) {
                    throw new RuntimeException('Failed to update teacher profile.');
                }
            }

            if ($role === 'Parent') {
                $sql = 'UPDATE `Parent` SET phone = ?, address = ? WHERE userID = ?';
                if ($this->query($sql, [$data['parent_profile_phone'] ?? '', $data['parent_profile_address'] ?? '', $userId]) === false) {
                    throw new RuntimeException('Failed to update parent profile.');
                }
            }

            $this->commit();
            return true;
        } catch (Exception $e) {
            if ($this->inTransaction()) { $this->rollBack(); }
            throw $e;
        }
    }

    public function deleteUser(int $userId)
    {
        $this->beginTransaction();
        try {
            $row = $this->fetchOne('SELECT Role FROM `User` WHERE userID = ? LIMIT 1', [$userId]);
            $role = $row['Role'] ?? null;

            if ($role === 'Parent') {
                $parentRow = $this->fetchOne('SELECT parentID FROM `Parent` WHERE userID = ? LIMIT 1', [$userId]);
                if ($parentRow) {
                    $parentID = $parentRow['parentID'];
                    // delete children linked to this parent
                    $this->query('DELETE FROM `Child` WHERE parentID = ?', [$parentID]);
                }
                $this->query('DELETE FROM `Parent` WHERE userID = ?', [$userId]);
            }

            if ($role === 'Teacher') {
                $this->query('DELETE FROM `Teacher` WHERE userID = ?', [$userId]);
            }

            if ($role === 'Admin') {
                $this->query('DELETE FROM `Admin` WHERE userID = ?', [$userId]);
            }

            // finally delete user row
            $this->query('DELETE FROM `User` WHERE userID = ?', [$userId]);

            $this->commit();
            return true;
        } catch (Exception $e) {
            if ($this->inTransaction()) { $this->rollBack(); }
            throw $e;
        }
    }
}

