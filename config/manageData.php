<?php
//include("db_conn.php");
//include($dir."/include/db_conn.php");
//Start Function insertData
function insertData($conn, $tableName, $data) {
    if (empty($tableName) || empty($data) || !is_array($data)) {
        return ['status' => 'error', 'message' => 'Invalid table name or data'];
    }

    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), '?'));
    $values = array_values($data);

    // Detect types: i = integer, d = double, s = string, b = blob
    $types = '';
    foreach ($values as $value) {
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_float($value)) {
            $types .= 'd';
        } elseif (is_string($value)) {
            $types .= 's';
        } else {
            $types .= 'b'; // Default to blob for unknown types
        }
    }

    $sql = "INSERT INTO `$tableName` ($columns) VALUES ($placeholders)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Preparation failed: ' . $conn->error];
    }

    // Bind parameters dynamically
    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) {
        return ['status' => 'success', 'message' => 'Data inserted successfully', 'insert_id' => $stmt->insert_id];
    } else {
        return ['status' => 'error', 'message' => 'Execution failed: ' . $stmt->error];
    }
}
//End Function insertData

//Start Function selectData
function selectData($mysqli, $table, $columns = '*', $where = [], $types = '', $order = '', $limit = '', $group = '')
{
    // 1. Build base SQL
    $sql = "SELECT $columns FROM `$table`";

    // 2. WHERE clause
    /*if (!empty($where)) {
        $whereClause = [];
        foreach ($where as $key => $value) {
            $whereClause[] = "`$key` = ?";
        }
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }*/
    if (!empty($where)) {
        $whereClause = [];
        foreach ($where as $key => $value) {
            // Semak sama ada ada operator
            if (preg_match('/\s*(\w+)\s+(<>|!=|=|>|<|LIKE)\s*/i', $key, $matches)) {
                $field = $matches[1];
                $operator = $matches[2];
                $whereClause[] = "`$field` $operator ?";
            } else {
                $whereClause[] = "`$key` = ?";
            }
        }
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }

    // 3. ORDER BY
    if (!empty($order)) {
        $sql .= " ORDER BY $order";
    }

    // 4. LIMIT
    if (!empty($limit)) {
        $sql .= " LIMIT $limit";
    }

    // 3. ORDER BY
    if (!empty($group)) {
        $sql .= " GROUP BY $group";
    }
    //echo $sql;
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $mysqli->error);
    }

    // 5. Bind parameters
    if (!empty($where) && !empty($types)) {
        $stmt->bind_param($types, ...array_values($where));
    }
    //echo "<br>".$sql."<br>";
    $stmt->execute();
    $result = $stmt->get_result();

    // 6. Return result object for while-loop fetching
    return $result;
}
//End Function selectData

//Start Function selectDataWithJoin
function selectDataWithJoin($mysqli, $table, $columns = '*', $joins = [], $where = [], $types = '', $order = '', $limit = '')
{
    // 1. Start SQL with base table and columns
    $sql = "SELECT $columns FROM $table";

    // 2. Append JOIN clauses
    if (!empty($joins)) {
        foreach ($joins as $join) {
            $sql .= " " . $join;
        }
    }

    // 3. WHERE clause
    /*if (!empty($where)) {
        $whereClause = [];
        foreach ($where as $key => $value) {
            $whereClause[] = "$key = ?";
        }
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }*/
    if (!empty($where)) {
        $whereClause = [];
        foreach ($where as $key => $value) {
            if (preg_match('/\s*([\w.]+)\s+(<>|!=|=|>|<|LIKE)\s*/i', $key, $matches)) {
                $field = $matches[1];
                $operator = $matches[2];
                if (strpos($field, '.') !== false) {
                    [$tbl, $col] = explode('.', $field, 2);
                    $field = "`$tbl`.`$col`";
                } else {
                    $field = "`$field`";
                }
                $whereClause[] = "$field $operator ?";
            } else {
                // Betulkan backtick untuk table.column
                if (strpos($key, '.') !== false) {
                    [$tbl, $col] = explode('.', $key, 2);
                    $key = "`$tbl`.`$col`";
                } else {
                    $key = "`$key`";
                }
                $whereClause[] = "$key = ?";
            }
        }
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }

    // 4. ORDER BY clause
    if (!empty($order)) {
        $sql .= " ORDER BY $order";
    }

    // 5. LIMIT clause
    if (!empty($limit)) {
        $sql .= " LIMIT $limit";
    }

    // 6. Prepare and execute statement
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $mysqli->error);
    }

    // 7. Bind parameters if WHERE exists
    if (!empty($where) && !empty($types)) {
        $stmt->bind_param($types, ...array_values($where));
    }
    //echo "<br>".$sql."<br>";
    $stmt->execute();
    return $stmt->get_result();
}

/*function selectDataWithJoin(
    $mysqli,
    $table,
    $columns = '*',
    $joins = [],
    $where = [],
    $types = '',
    $order = '',
    $limit = '',
    $groupBy = '' // ✅ Tambahan parameter group by
) {
    // 1. Start SQL with base table and columns
    $sql = "SELECT $columns FROM $table";

    // 2. Append JOIN clauses
    if (!empty($joins)) {
        foreach ($joins as $join) {
            $sql .= " " . $join;
        }
    }

    // 3. WHERE clause
    if (!empty($where)) {
        $whereClause = [];
        foreach ($where as $key => $value) {
            $whereClause[] = "$key = ?";
        }
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }

    // 4. GROUP BY clause ✅
    if (!empty($groupBy)) {
        $sql .= " GROUP BY $groupBy";
    }

    // 5. ORDER BY clause
    if (!empty($order)) {
        $sql .= " ORDER BY $order";
    }

    // 6. LIMIT clause
    if (!empty($limit)) {
        $sql .= " LIMIT $limit";
    }
    
    // 7. Prepare and execute statement
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $mysqli->error);
    }

    // 8. Bind parameters if WHERE exists
    if (!empty($where)) {
        // Auto detect types if not provided
        if (empty($types)) {
            foreach ($where as $value) {
                if (is_int($value)) {
                    $types .= 'i';
                } elseif (is_float($value)) {
                    $types .= 'd';
                } elseif (is_string($value)) {
                    $types .= 's';
                } else {
                    $types .= 'b';
                }
            }
        }
        $stmt->bind_param($types, ...array_values($where));
    }

    // Optional debug
     echo "<br>$sql<br>";

    $stmt->execute();
    return $stmt->get_result();
}
*/
//End Function selectDataWithJoin

//Start Function updateData
function updateData($mysqli, $table, $data, $where) {
    if (empty($table) || empty($data) || empty($where)) {
        return ['status' => 'error', 'message' => 'Table, data, or where clause is missing'];
    }

    // Build SET part dynamically
    $set = '';
    $params = [];
    $types = '';

    foreach ($data as $column => $value) {
        $set .= "$column = ?, ";
        $params[] = $value;

        // Determine data type
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_double($value)) {
            $types .= 'd';
        } elseif (is_string($value)) {
            $types .= 's';
        } else {
            $types .= 'b'; // blob or unknown
        }
    }

    $set = rtrim($set, ', ');

    // WHERE clause (assumes simple key-value)
    $where_clause = '';
    foreach ($where as $key => $val) {
        $where_clause .= "$key = ? AND ";
        $params[] = $val;

        // Determine data type for WHERE
        if (is_int($val)) {
            $types .= 'i';
        } elseif (is_double($val)) {
            $types .= 'd';
        } elseif (is_string($val)) {
            $types .= 's';
        } else {
            $types .= 'b';
        }
    }

    $where_clause = rtrim($where_clause, ' AND ');

    $sql = "UPDATE $table SET $set WHERE $where_clause";
    //return $sql;
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error];
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['status' => 'success', 'message' => 'Data updated successfully'];
    } else {
        return ['status' => 'info', 'message' => 'No rows were updated'];
    }
}
//End Function updateData

//Start Function deleteData
function deleteData($mysqli, $table, $where) {
    if (empty($table) || empty($where)) {
        return ['status' => 'error', 'message' => 'Table name or WHERE condition is missing'];
    }

    // Build WHERE clause
    $conditions = [];
    $types = '';
    $params = [];

    foreach ($where as $column => $value) {
        $conditions[] = "$column = ?";
        $params[] = $value;

        // Determine type
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_float($value)) {
            $types .= 'd';
        } elseif (is_string($value)) {
            $types .= 's';
        } else {
            $types .= 'b';
        }
    }

    $where_clause = implode(" AND ", $conditions);

    $sql = "DELETE FROM $table WHERE $where_clause";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error];
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['status' => 'success', 'message' => 'Data deleted successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Delete failed or no matching data'];
    }
}
//End Function deleteData

//start get row count
function getRowCount($mysqli, $table, $where = [], $types = '') {
    $sql = "SELECT COUNT(*) as total FROM $table";
    $params = [];
    $conditions = [];

    // Bina WHERE clause jika ada
    /*if (!empty($where)) {
        foreach ($where as $column => $value) {
            $conditions[] = "$column = ?";
            $params[] = $value;
        }
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }*/
    if (!empty($where)) {
        $whereClause = [];
        foreach ($where as $key => $value) {
            // Semak sama ada ada operator
            if (preg_match('/\s*(\w+)\s+(<>|!=|=|>|<|LIKE)\s*/i', $key, $matches)) {
                $field = $matches[1];
                $operator = $matches[2];
                $whereClause[] = "`$field` $operator ?";
            } else {
                $whereClause[] = "`$key` = ?";
            }
            $params[] = $value;
        }
        
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }
    

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    // Bind parameter jika ada
    if (!empty($params)) {
        if (empty($types)) {
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b';
                }
            }
        }
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total']; // return bilangan baris
}
//end get row count
?>
