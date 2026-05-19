<?php
require_once 'Database.php';
require_once 'User.php';
require_once 'Parent.php';
require_once 'Teacher.php';
require_once 'Admin.php';

class UserRepository {
    public function findByEmail(string $email): ?User
    {
        $row = $this->fetchUserRowByEmail($email);
        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    public function findById(int $userId): ?User
    {
        $row = $this->fetchUserRowById($userId);
        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    private function fetchUserRowByEmail(string $email): ?array
    {
        $queries = [
            ["SELECT * FROM Users WHERE email = ?", [$email]],
            ["SELECT * FROM User WHERE email = ?", [$email]],
        ];

        return $this->executeUserRowQueries($queries);
    }

    private function fetchUserRowById(int $userId): ?array
    {
        $queries = [
            ["SELECT * FROM Users WHERE userId = ?", [$userId]],
            ["SELECT * FROM User WHERE userID = ?", [$userId]],
        ];

        return $this->executeUserRowQueries($queries);
    }

    private function executeUserRowQueries(array $queries): ?array
    {
        foreach ($queries as [$sql, $params]) {
            try {
                $row = Database::getInstance()->fetchOne($sql, $params);
                if (!empty($row)) {
                    return $row;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return null;
    }

    public function save(User $user): bool
    {
        if ($user->getId() === null) {
            return $this->insert($user);
        }

        return $this->update($user);
    }

    public function create(User $user): bool
    {
        return $this->save($user);
    }

    private function insert(User $user): bool
    {
        $sql = "INSERT INTO Users (email, password, firstName, lastName, preferredLanguage, createdAt, lastLoginAt, role, isActive)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $user->getEmail(),
            $user->getPasswordHash(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getPreferredLanguage(),
            $user->getCreatedAt(),
            $user->getLastLoginAt(),
            $user->getRole(),
            $user->isActive() ? 1 : 0
        ];

        $stmt = Database::getInstance()->query($sql, $params);
        if (!$stmt) {
            return false;
        }

        $id = Database::getInstance()->getConnection()->lastInsertId();
        if ($id) {
            $user->setId((int)$id);
        }

        return true;
    }

    private function update(User $user): bool
    {
        $sql = "UPDATE Users SET email = ?, password = ?, firstName = ?, lastName = ?, preferredLanguage = ?, createdAt = ?, lastLoginAt = ?, role = ?, isActive = ? WHERE userId = ?";

        $params = [
            $user->getEmail(),
            $user->getPasswordHash(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getPreferredLanguage(),
            $user->getCreatedAt(),
            $user->getLastLoginAt(),
            $user->getRole(),
            $user->isActive() ? 1 : 0,
            $user->getId()
        ];

        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt !== false;
    }

    private function mapRowToUser(array $row)
    {
        $role = strtolower($row['role'] ?? $row['Role'] ?? '');
        $data = [
            'userId' => $row['userId'] ?? $row['UserId'] ?? $row['userID'] ?? null,
            'email' => $row['email'] ?? $row['Email'] ?? '',
            'password' => $row['password'] ?? $row['Password'] ?? $row['passwordHash'] ?? $row['passwordhash'] ?? '',
            'preferredLanguage' => $row['preferredLanguage'] ?? $row['PreferredLanguage'] ?? $row['preferredlanguage'] ?? null,
            'createdAt' => $row['createdAt'] ?? $row['CreatedAt'] ?? null,
            'lastLoginAt' => $row['lastLoginAt'] ?? $row['LastLoginAt'] ?? null,
            'role' => $row['role'] ?? $row['Role'] ?? null,
            'firstName' => $row['firstName'] ?? $row['firstname'] ?? $row['FirstName'] ?? null,
            'lastName' => $row['lastName'] ?? $row['Lastname'] ?? $row['LastName'] ?? null,
            'isActive' => $row['isActive'] ?? $row['IsActive'] ?? $row['isActive'] ?? 1,
        ];

        if ($role === 'teacher') {
            return new Teacher(
                $data['userId'],
                $data['email'],
                $data['password'],
                $data['preferredLanguage'],
                $data['createdAt'],
                $data['lastLoginAt'],
                $data['role'],
                $data['firstName'],
                $data['lastName']
            );
        }

        if ($role === 'parent' || $role === 'parents') {
            return new Parents(
                $data['userId'],
                $data['email'],
                $data['password'],
                $data['preferredLanguage'],
                $data['createdAt'],
                $data['lastLoginAt'],
                $data['role'],
                $data['firstName'],
                $data['lastName']
            );
        }

        if ($role === 'admin') {
            return new Admin(
                $data['userId'],
                $data['email'],
                $data['password'],
                $data['preferredLanguage'],
                $data['createdAt'],
                $data['lastLoginAt'],
                $data['role'],
                $data['firstName'],
                $data['lastName']
            );
        }

        return User::fromArray($data);
    }
}
?>`