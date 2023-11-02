<?php

namespace Modules\Core\Helpers;

interface RepositoryInterface  {

    public function all(array $param);

    public function get($id);

    public function store(array $data);

    public function update($id, array $data);

    public function delete($id);
    
}

?>