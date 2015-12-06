<?php

namespace User\Model\Db\Mysql;

use Aston\Db\Connection;
use Aston\Db\Exception\DataNotFound;
use DateTime;
use PDO;
use User\Model\User as UserModel;
use User\Model\UserHandler;

class User implements UserHandler {

    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return PDO
     */
    public function getDb()
    {
        return $this->db->getPdo();
    }

    public function delete(UserModel $user)
    {
        $sql = "DELETE FROM user WHERE id=?";
        $this->getDb()->beginTransaction();

        try {
            $stmt = $this->getDb()->prepare($sql);
            $stmt->bindValue(1, $user->getId());
            $stmt->execute();
            $this->getDb()->commit();
        } catch (\PDOException $e) {
            $this->getDb()->rollBack();
            throw $e;
        }
    }

    public function find(array $criteria = array())
    {
        $where = $this->where($criteria);
        $sql = "SELECT * FROM user" . $where['sql'];

        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute($where['bind']);
        $result = $stmt->fetch();

        return $this->rowToObject($result);
    }

    protected function where(array $criteria = array())
    {
        $baseModel = array(
            'where'   => array(),
            'limit'   => array(null, null),
            'orderBy' => array(),
        );

        $sql      = '';
        $result   = array();
        $criteria = array_merge($baseModel, $criteria);

        if (is_array($criteria['where']) && !empty($criteria['where'])) {
            $sql .= ' WHERE ';

            foreach ($criteria['where'] as $i => $where) {
                if ($i > 0 && !$where['operator']) {
                    continue;
                }

                foreach($where as $key => $value) {
                    if ($key != 'operator') {
                        $sql .= $key . '=? ' ;
                        $result['bind'][] = $value;
                    } else {
                        $sql .= strtoupper($value) . ' ';
                    }
                }
            }
        }

        if ($criteria['limit'][0] >= 0 && $criteria['limit'][1] >= 1) {
            $sql .= 'LIMIT '.$criteria['limit'][0].','.$criteria['limit'][1];
        }

        $result['sql'] = $sql;
        return $result;
    }

    public function findAll(array $criteria = array())
    {
        $stmt = $this->getDb()->prepare('SELECT * FROM user');
        $stmt->execute();
        $result = $stmt->fetchAll();
        $users = [];

        foreach ($result as $row) {
            array_push($users, $this->rowToObject($row));
        }
        return $users;
    }

    public function findByEmail($email)
    {
        $sql = 'SELECT * FROM user WHERE email=?';
        $stmt = $this->getDb()->prepare($sql);
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch();

        if (!$data) {
            throw new DataNotFound($email . ' not found');
        }

        return $this->rowToObject($data);
    }

    public function insert(UserModel $user)
    {
        $sql = 'INSERT INTO user VALUES (' .
                'NULL, ' .
                "'" . $user->getEmail() . "'," .
                "'" . $user->getUsername() . "'," .
                "'" . $user->getPassword() . "'," .
                "'" . $user->getFirstname() . "'," .
                "'" . $user->getLastname() . "'," .
                "'" . $user->getBirthdate()->format('Y-m-d') . "'," .
                "'" . (int) $user->getIsActive() . "'" .
                ")";

        return $this->getDb()->exec($sql);
    }

    public function update(UserModel $user)
    {
        $sql = 'UPDATE user SET
                email=:email,
                username=:username,
                password=:password,
                firstname=:firstname,
                lastname=:lastname,
                birthdate=:birthdate,
                isActive=:isActive
                WHERE id=:id
               ';

        $this->getDb()->beginTransaction();

        try {
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute(array(
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'birthdate' => $user->getBirthdate()->format('Y-m-d'),
                'isActive' => $user->getIsActive(),
                'id' => (int) $user->getId(),
            ));
            $this->getDb()->commit();
        } catch (PDOException $e) {
            $this->getDb()->rollBack();
            throw $e;
        }
    }

    public function rowToObject($result)
    {
        $user = new UserModel();
        $user->setId($result['id'])
                ->setEmail($result['email'])
                ->setFirstname($result['firstname'])
                ->setLastname($result['lastname'])
                ->setIsActive($result['isActive'])
                ->setBirthdate(new DateTime($result['birthdate']))
                ->setUsername($result['username'])
                ->setPassword($result['password']);

        return $user;
    }

}
