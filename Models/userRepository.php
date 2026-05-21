<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Parent.php';
require_once __DIR__ . '/Teacher.php';
require_once __DIR__ . '/Admin.php';

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

    public function findByName(string $fullName): array
    {
        $fullName = trim($fullName);
        if ($fullName === '') {
            return [];
        }

        $like = "%{$fullName}%";
        $queries = [
            ["SELECT * FROM User WHERE firstname LIKE ? OR Lastname LIKE ? OR CONCAT(firstname, ' ', Lastname) LIKE ?", [$like, $like, $like]],
            ["SELECT * FROM Users WHERE firstname LIKE ? OR Lastname LIKE ? OR CONCAT(firstname, ' ', Lastname) LIKE ?", [$like, $like, $like]],
        ];

        $rows = [];
        foreach ($queries as [$sql, $params]) {
            try {
                $fetched = Database::getInstance()->fetchAll($sql, $params);
                if (is_array($fetched) && count($fetched) > 0) {
                    $rows = array_merge($rows, $fetched);
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return array_map([$this, 'mapRowToUser'], $rows);
    }

    public function findByIdAndName(int $userId, string $fullName): ?User
    {
        $fullName = trim($fullName);
        if ($userId <= 0 || $fullName === '') {
            return null;
        }

        $searchTerms = array_filter(preg_split('/\s+/', preg_replace('/[^a-zA-Z0-9]+/', ' ', $fullName)), fn($term) => strlen($term) >= 2);
        if (empty($searchTerms)) {
            return null;
        }

        $nameConditions = array_fill(0, count($searchTerms), '(firstname LIKE ? OR Lastname LIKE ?)');
        $nameSql = implode(' AND ', $nameConditions);
        $params = [$userId];

        foreach ($searchTerms as $term) {
            $like = '%' . $term . '%';
            $params[] = $like;
            $params[] = $like;
        }

        $queries = [
            ["SELECT * FROM User WHERE userID = ? AND {$nameSql}", $params],
            ["SELECT * FROM Users WHERE userId = ? AND {$nameSql}", $params],
        ];

        foreach ($queries as [$sql, $queryParams]) {
            try {
                $row = Database::getInstance()->fetchOne($sql, $queryParams);
                if (!empty($row)) {
                    return $this->mapRowToUser($row);
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return null;
    }

    private function fetchUserRowByEmail(string $email): ?array
    {
        $queries = [
            ["SELECT * FROM User WHERE email = ?", [$email]],
            ["SELECT * FROM Users WHERE email = ?", [$email]],
        ];

        return $this->executeUserRowQueries($queries);
    }

    private function fetchUserRowById(int $userId): ?array
    {
        $queries = [
            ["SELECT * FROM User WHERE userID = ?", [$userId]],
            ["SELECT * FROM Users WHERE userId = ?", [$userId]],
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
        $sql = "INSERT INTO User (email, passwordHash, firstname, Lastname, preferredLanguage, createdAt, lastLoginAt, Role, isActive)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $user->getEmail(),
            $user->getPasswordHash(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getPreferredLanguage(),
            $user->getCreatedAt(),
            $user->getLastLoginAt(),
            ucfirst($user->getRole()),
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
        $sql = "UPDATE User SET email = ?, passwordHash = ?, firstname = ?, Lastname = ?, preferredLanguage = ?, createdAt = ?, lastLoginAt = ?, Role = ?, isActive = ? WHERE userID = ?";

        $params = [
            $user->getEmail(),
            $user->getPasswordHash(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getPreferredLanguage(),
            $user->getCreatedAt(),
            $user->getLastLoginAt(),
            ucfirst($user->getRole()),
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
            'role' => strtolower($row['role'] ?? $row['Role'] ?? ''),
            'firstName' => $row['firstName'] ?? $row['firstname'] ?? $row['FirstName'] ?? null,
            'lastName' => $row['lastName'] ?? $row['Lastname'] ?? $row['LastName'] ?? null,
            'isActive' => $row['isActive'] ?? $row['IsActive'] ?? 1,
        ];

        // For the API we return a simple User instance to avoid pulling
        // in other model classes which may include view files.
        return User::fromArray($data);
    }
}
