<?php

namespace User\Model;

interface UserHandler
{
    public function find(array $criteria = array());
    public function findAll(array $criteria = array());
    public function findByEmail($email);
    public function insert(User $user);
    public function update(User $user);
    public function delete(User $user);
}
