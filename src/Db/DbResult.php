<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * https://codigonaranja.com/como-cambiar-el-color-del-texto-en-aplicaciones-de-consola-de-php
 */
namespace HNova\Rest\Db;

use PDO;
use PDOStatement;

class DbResult
{
    public function __construct(public PDOStatement $stmt)
    {
        
    }

    public function getRowCount():int{
        return $this->stmt->rowCount();
    }

    public function getRows(int $mode = PDO::FETCH_ASSOC, mixed ...$args):array{
        return $this->stmt->fetchAll($mode, ...$args);
    }
}