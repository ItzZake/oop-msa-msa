<?php
require_once 'Database.php';
require_once 'User.php';
require_once 'Parent.php';
require_once 'Teacher.php';
require_once 'Admin.php';

class UserRepository {
    public function findByEmail(string $email): ?User
    {
        $row = Database::getInstance()->fetchOne("SELECT * FROM Users WHERE email = ?", [$email]);
        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    public function findById(int $userId): ?User
    {
        $row = Database::getInstance()->fetchOne("SELECT * FROM Users WHERE userId = ?", [$userId]);
        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
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
            'userId' => isset($row['userId']) ? $row['userId'] : (isset($row['UserId']) ? $row['UserId'] : null),
            'email' => $row['email'] ?? $row['Email'] ?? '',
            'password' => $row['password'] ?? $row['Password'] ?? '',
            'preferredLanguage' => $row['preferredLanguage'] ?? $row['PreferredLanguage'] ?? null,
            'createdAt' => $row['createdAt'] ?? $row['CreatedAt'] ?? null,
            'lastLoginAt' => $row['lastLoginAt'] ?? $row['LastLoginAt'] ?? null,
            'role' => $row['role'] ?? $row['Role'] ?? null,
            'firstName' => $row['firstName'] ?? $row['FirstName'] ?? null,
            'lastName' => $row['lastName'] ?? $row['LastName'] ?? null,
            'isActive' => $row['isActive'] ?? $row['IsActive'] ?? 1,
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