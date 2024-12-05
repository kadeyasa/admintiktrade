<?php 
    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    }catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }

    function getenergypackages(){
        global $conn;
        $sql = "SELECT * FROM pointcard_package ORDER BY price ASC ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $datas;
    }

    function insertpackage($name='',$price='',$energy=''){
        global $conn;
        $date = date('Y-m-d');
    
        // Prepare the SQL insert statement
        $sql = "INSERT INTO pointcard_package (created_date, package_name, price, getpoint, status) 
                VALUES (:created_date, :package_name, :price, :getpoint, :status)";
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':created_date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':package_name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':getpoint', $energy, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);

        // Set the status
        $status = 1;

        // Execute the statement
        $save = $stmt->execute();
        //$conn = null;
        return $save;
    }

    function insertData($table, $data) {

        global $conn;
    
        // Ensure the necessary variables are defined
        if (empty($table) || empty($data) || !is_array($data)) {
            throw new InvalidArgumentException('Invalid table name or data array.');
        }
    
        // Get current date
        $date = date('Y-m-d');
    
        // Construct the base SQL insert statement
        $fields = array_keys($data);

        $placeholders = array_map(function($field) {
            return ':' . $field;
        }, $fields);
        
        // Construct the full SQL query
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );
    
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);
    
        // Bind the parameters
        foreach ($data as $field => $value) {
            $stmt->bindValue(':' . $field, $value);
        }
    
        // Execute the statement and return the result
        try {
            $save = $stmt->execute();
            return $save;
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    function updateData($table, $data, $conditions) {
        global $conn;
    
        // Ensure the necessary variables are defined
        if (empty($table) || empty($data) || !is_array($data) || empty($conditions) || !is_array($conditions)) {
            throw new InvalidArgumentException('Invalid table name, data array, or conditions array.');
        }
    
        // Construct the SET part of the SQL statement
        $setClauses = [];
        foreach ($data as $field => $value) {
            $setClauses[] = "$field = :set_$field";
        }
        $setClause = implode(', ', $setClauses);
    
        // Construct the WHERE part of the SQL statement
        $whereClauses = [];
        foreach ($conditions as $field => $value) {
            $whereClauses[] = "$field = :where_$field";
        }
        $whereClause = implode(' AND ', $whereClauses);
    
        // Construct the full SQL query
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $table,
            $setClause,
            $whereClause
        );
    
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);
    
        // Bind the parameters for SET clause
        foreach ($data as $field => $value) {
            $stmt->bindValue(':set_' . $field, $value);
        }
    
        // Bind the parameters for WHERE clause
        foreach ($conditions as $field => $value) {
            $stmt->bindValue(':where_' . $field, $value);
        }
    
        // Execute the statement and return the result
        try {
            $update = $stmt->execute();
            return $update;
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    

    function getdatas($table, $fields = '*', $where = [],$orderby='',$limit=0,$offest=0,$search='') {
        global $conn;

        try {
            // Ensure the necessary variables are defined and sanitized
            if (is_array($fields)) {
                $fields = implode(', ', array_map('sanitizeColumnName', $fields));
            } elseif ($fields=='*') {
                $fields = '*';
            } else {
                $fields = sanitizeColumnName($fields);
            }

            // Sanitize table name
            $table = sanitizeTableName($table);

            // Construct the base SQL query
            $sql = "SELECT $fields FROM $table";

            // Prepare the WHERE clause if conditions are provided
            if (!empty($where)) {
                $conditions = [];
                foreach ($where as $column => $value) {
                    $conditions[] = sanitizeColumnName($column) . " = :" . sanitizeColumnName($column);
                }
                $whereClause = ' WHERE ' . implode(' AND ', $conditions);
                $sql .= $whereClause;
            }

            // Append the ORDER BY clause
            if (empty($orderby)) {
                $sql .= " ORDER BY id ASC";
            } else {
                //$orderby = sanitizeColumnName($orderby);
                $sql .= " ORDER BY $orderby";
            }

            // Append the LIMIT clause
            if (!empty($limit) && is_numeric($limit) && $limit > 0) {
                if (!empty($offset) && is_numeric($offset) && $offset >= 0) {
                    $sql .= " LIMIT $offset, $limit";
                } else {
                    $sql .= " LIMIT $limit";
                }
            }

           
            // Prepare the SQL statement
            $stmt = $conn->prepare($sql);

            // Bind the parameters
            foreach ($where as $column => &$value) {
                //echo $sql.' '.$column.' '.$value;
                $stmt->bindParam(':' . sanitizeColumnName($column), $value, PDO::PARAM_STR);
            }

            // Execute the statement
            $stmt->execute();

            // Fetch the results
            $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the fetched data
            return $datas;
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return $e->getMessage();
        }
    }

    // Functions to sanitize inputs
    function sanitizeColumnName($column) {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $column);
    }

    function sanitizeTableName($table) {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    }
    

    function getdata($table,$id,$field=''){
        global $conn;
        if($field==''){
            $field='id';
        }
        $sql = "SELECT * FROM " . $table . " WHERE ".$field." = :id";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        //$stmt->bindParam(':table', $table, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        //$conn = null;
        return $data;
    }


    function countingdata($table){
        $sql = "SELECT count(*) AS total FROM " . $table ;
        $stmt = $conn->prepare($sql);
        // Execute the statement
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        //$conn = null;
        return $data;
    }
    
    

    function deletedata($table,$id){
        global $conn;
        $sql = "DELETE FROM " . $table . " WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        //$stmt->bindParam(':table', $table, PDO::PARAM_STR);

        // Execute the statement
        $delete = $stmt->execute();
        //$conn = null;
        return $delete;
    }

    function _deletedata($table,$id){
        global $conn;
        $sql = "UPDATE " . $table . " SET deleted_date='".date('Y-m-d H:i:s')."' WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        //$stmt->bindParam(':table', $table, PDO::PARAM_STR);

        // Execute the statement
        $delete = $stmt->execute();
        //$conn = null;
        return $delete;
    }

    function summarytablepointcard($table,$field,$user_id){
        global $conn;
        $sql = "SELECT SUM($field) AS total FROM $table WHERE user_id=:user_id AND $field>0";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function summarytableaccountdeposit($table,$user_id){
        global $conn;
        $sql = "SELECT SUM(amount) AS total FROM $table WHERE user_id=:user_id AND (status=2 OR status=1)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function summarytableaccountbalance($table,$field,$user_id){
        global $conn;
        $sql = "SELECT SUM($field) AS total FROM $table WHERE user_id=:user_id AND (decription='Buy Point Card' OR decription LIKE '%Upgrade to Package%')";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function getdatabalances($table,$field,$user_id,$offset=0,$limit=0){
        global $conn;
        if($limit>0){
            $sql = "SELECT * FROM $table WHERE user_id=:user_id AND credit>0 AND (decription LIKE '%Upgrade to Package%') LIMIT $limit OFFSET $offset";
        }else{
            $sql = "SELECT * FROM $table WHERE user_id=:user_id AND credit>0 AND (decription='Buy Point Card' OR decription LIKE '%Upgrade to Package%')";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    function getgeonology($parent_id=''){
        global $conn;
        $stmt = $conn->prepare("
            SELECT m1.id, m1.ref_code, m1.username, COUNT(m2.id) AS downline_count, m1.turnover,m1.teamstrade,m1.personaltrade,
            (SELECT SUM(credit) FROM account_balance_histories WHERE user_id = m1.id AND decription='Buy Point Card') AS total_balance_in
            FROM member m1
            LEFT JOIN member m2 ON m1.ref_code = m2.upline_id
            WHERE m1.upline_id = :parent_id
            GROUP BY m1.id,m1.ref_code,m1.username, m1.turnover,m1.teamstrade,m1.personaltrade
        ");
        $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_STR);
       
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        echo json_encode($data);
    }

    function getuseromset($parent_id='',$start_date='',$end_date='',$limit=100,$offset=0){
        global $conn;
        if($parent_id=='' && $start_date=='' && $end_date==''){
            $sql="
                SELECT 
                    n.upline_id,
                    SUM(abh.credit) AS total_credit,
                    m.username,m.ref_code
                FROM 
                    public.networks n 
                LEFT JOIN 
                    public.account_balance_histories abh ON n.member_id = abh.user_id
                LEFT JOIN 
                    member m ON m.id=n.upline_id
                WHERE 
                    abh.decription = 'Buy Point Card' AND m.upline_id=''
                GROUP BY 
                    n.upline_id,m.username,m.ref_code
                ORDER BY 
                    n.upline_id
                LIMIT :limit OFFSET :offset;
            ";
        }else{
            if($parent_id!='' && $start_date!='' && $end_date!=''){
                $sql="
                    SELECT 
                        n.upline_id,
                        SUM(abh.credit) AS total_credit,
                        m.username,m.ref_code
                    FROM 
                        public.networks n
                    LEFT JOIN 
                        public.account_balance_histories abh ON n.member_id = abh.user_id
                    LEFT JOIN 
                        member m ON m.id=n.upline_id,m.ref_code
                    WHERE 
                        abh.decription = 'Buy Point Card' AND m.upline_id='$parent_id' AND created_date BETWEEN '$start_date' AND '$end_date'
                    GROUP BY 
                        n.upline_id,m.username
                    ORDER BY 
                        n.upline_id
                    LIMIT :limit OFFSET :offset;
                ";
            }else if($parent_id=='' && $start_date!='' && $end_date!=''){
                $sql="
                    SELECT 
                        n.upline_id,
                        SUM(abh.credit) AS total_credit,
                        m.username,m.ref_code
                    FROM 
                        public.networks n
                    LEFT JOIN 
                        public.account_balance_histories abh ON n.member_id = abh.user_id
                    LEFT JOIN 
                        member m ON m.id=n.upline_id
                    WHERE 
                        abh.decription = 'Buy Point Card' AND created_date BETWEEN '$start_date' AND '$end_date'
                    GROUP BY 
                        n.upline_id,m.username,m.ref_code
                    ORDER BY 
                        n.upline_id
                    LIMIT :limit OFFSET :offset;
                ";
            }else{
                $sql="
                    SELECT 
                        n.upline_id,
                        SUM(abh.credit) AS total_credit,
                        m.username,m.ref_code
                    FROM 
                        public.networks n
                    LEFT JOIN 
                        public.account_balance_histories abh ON n.member_id = abh.user_id
                    LEFT JOIN 
                        member m ON m.id=n.upline_id
                    WHERE 
                        abh.decription = 'Buy Point Card' AND m.upline_id='$parent_id'
                    GROUP BY 
                        n.upline_id,m.username,m.ref_code
                    ORDER BY 
                        n.upline_id
                    LIMIT :limit OFFSET :offset;
                ";
            }
        }
        //echo $sql;
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_STR);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    function getdatawithdraws($limit=0,$offset=0,$status=0){
        global $conn;
        if($limit>0){
            $sql = "SELECT a.*,b.username,email FROM withdraw a JOIN  member b ON b.id=a.user_id WHERE a.deleted_date IS NULL and a.status = :status ORDER BY id DESC LIMIT :limit OFFSET :offset";
        }else{
            $sql = "SELECT a.*,b.username,email FROM withdraw a JOIN  member b ON b.id=a.user_id WHERE a.deleted_date IS NULL and a.status = :status ORDER BY id DESC ";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        if($limit>0){
            $stmt->bindParam(':limit', $limit, PDO::PARAM_STR);
            $stmt->bindParam(':offset', $offest, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    function updatebalancereject($id){
        global $conn;
        // First SQL statement to fetch data
        $sql = "SELECT * FROM withdraw a WHERE a.id = :id AND status = 0 ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Retrieve the amount from the fetched data
            $amount = $data['amount'];
            
            // Second SQL statement to update account balance
            $sql_update = "UPDATE account_balance SET balance = balance + :amount WHERE user_id = :user_id";
            $stmt = $conn->prepare($sql_update);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);  // Use PARAM_STR for the amount if it includes decimals
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_STR);
            $stmt->execute();

            // Third SQL statement to update withdraw status
            $sql_update_wd = "UPDATE withdraw SET status = :status WHERE id = :id";
            $stmt = $conn->prepare($sql_update_wd);
            $status = 5;  // Define status as an integer
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->execute();

                return "Account balance updated successfully.";
            } else {
                return "No data found for the provided id.";
            }
        }

    function getsumpointcardbuy(){
        global $conn;
        $sql ="SELECT SUM(credit) AS totalcredit FROM account_balance_histories WHERE decription='Buy Point Card'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function gettotalrewards(){
        global $conn;
        $sql="SELECT (
                (SELECT SUM(debet) 
                FROM account_balance_histories 
                WHERE created_date >= '2024-05-10' 
                AND (decription LIKE '%Rewards%' OR decription LIKE '%Bonus%')
                ) 
                - 
                (SELECT SUM(debet) 
                FROM account_balance_histories 
                WHERE created_date >= '2024-05-10' 
                AND decription LIKE '%Claim%'
                )
            ) AS difference;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;  
    }

    function totalclaim(){
        global $conn;
        $sql="SELECT (
                
                (SELECT SUM(debet) 
                FROM account_balance_histories 
                WHERE created_date >= '2024-05-10' 
                AND decription LIKE '%Claim%'
                )
            ) AS difference;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;  
    }

    function totalremaining(){
        global $conn;
        $sql="select sum(remaining_balance) as totalbonus from account_balance_bonus";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;  
    }

    function getdatarewards($start = 0, $offset = 0, $keyword = '') {
        global $conn; // Assuming $conn is your PDO connection object
    
        // Base SQL query
        $sql = "SELECT a.*, b.username FROM account_balance_bonus a JOIN member b ON a.user_id = b.id";
    
        // Conditionally add WHERE clause for keyword search
        if (!empty($keyword)) {
            $where = " WHERE b.username LIKE :keyword";
            $sql .= $where;
        }
    
        // Conditionally add LIMIT and OFFSET clauses
        if ($offset > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
    
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);
    
        // Bind parameters
        if (!empty($keyword)) {
            $keywordParam = "%$keyword%";
            $stmt->bindParam(':keyword', $keywordParam, PDO::PARAM_STR);
        }
        if ($offset > 0) {
            $stmt->bindParam(':limit', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $start, PDO::PARAM_INT);
        }
    
        // Execute the query
        $stmt->execute();
    
        // Fetch all rows as associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $data;
    }
    
    function getdatacutbalances($limit=0,$offset=0,$keyword='',$start='',$end=''){
        global $conn;
       
        $sql = "SELECT a.*, b.username FROM pointcard_histories a JOIN member b ON a.user_id = b.id WHERE a.balance_out>0";
       

        if($keyword!=''){
            $sql.=" AND b.username LIKE :keyword";
            //$stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        }

        if($limit!=0 && $offset!=0){
            $sql .= " LIMIT :limit OFFSET :offset";
            //$stmt->bindParam(':limit', $offset, PDO::PARAM_INT);
            //$stmt->bindParam(':offset', $limit, PDO::PARAM_INT);
        }

        if($start!='' && $end!=''){
            $sql.=" AND a.created_at>=:start AND a.created_at<=:end";
            //$stmt->bindParam(':start', $start, PDO::PARAM_INT);
            //$stmt->bindParam(':end', $end, PDO::PARAM_INT);
        }
        $stmt = $conn->prepare($sql);

        if($keyword!=''){
            //$sql.=" AND b.username LIKE :keyword";
            $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        }

        if($limit!=0 && $offset!=0){
            //$sql .= " LIMIT :limit OFFSET :offset";
            $stmt->bindParam(':limit', $limit, PDO::PARAM_STR);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_STR);
        }

        if($start!='' && $end!=''){
            //$sql.=" AND created_date>=:start AND created_date<=:end";
            $stmt->bindParam(':start', $start, PDO::PARAM_STR);
            $stmt->bindParam(':end', $end, PDO::PARAM_STR);
        }
        //echo $sql;
        // Execute the query
        $stmt->execute();
    
        // Fetch all rows as associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $data;
    }

    function getmemberpro($limit=0,$offest=0,$keyword='',$start_date='',$end_date=''){
        global $conn;
        $sql ="SELECT a.*,b.username FROM account_balance_histories a JOIN member b ON a.user_id=b.id where decription LIKE '%Upgrade to%' ";
        
        if($keyword!=''){
            $sql.=" AND b.username LIKE :keyword";
            //$stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        }

        if($limit>0){
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        if($start_date!='' && $end_date!=''){
            $sql.=" AND a.created_date>=:start AND a.created_date<=:end";
            //$stmt->bindParam(':start', $start, PDO::PARAM_INT);
            //$stmt->bindParam(':end', $end, PDO::PARAM_INT);
        }

        $sql.=" ORDER BY a.created_date ASC";

        $stmt = $conn->prepare($sql);

        if($keyword!=''){
            //$sql.=" AND b.username LIKE :keyword";
            $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        }

        if($limit!=0 && $offset!=0){
            //$sql .= " LIMIT :limit OFFSET :offset";
            $stmt->bindParam(':limit', $limit, PDO::PARAM_STR);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_STR);
        }
        
        if($start_date!='' && $end_date!=''){
            //$sql.=" AND created_date>=:start AND created_date<=:end";
            $stmt->bindParam(':start', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end', $end_date, PDO::PARAM_STR);
        }

        //echo $sql;
        $stmt->execute();
    
        // Fetch all rows as associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $data;
    }

    function getmemberupgraded($start_date='',$end_date=''){
        global $conn;
        if($start_date==''){
            $sql ="SELECT COUNT(*) AS total,sum(credit) AS totalcredit FROM account_balance_histories WHERE decription LIKE '%Upgrade to%'";
        }else{
            $sql ="SELECT COUNT(*) AS total,sum(credit) AS totalcredit FROM account_balance_histories WHERE decription LIKE '%Upgrade to%' AND created_date>='$start_date' AND created_date<='$end_date'";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    

    function summarydeposit(){
        global $conn;
        $sql ="SELECT SUM(amount) AS totaldeposit FROM account_deposit WHERE (status=1 OR status=2)";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function summarybuyenergy(){
        global $conn;
        $sql ="SELECT SUM(credit) AS totalbuyenergy FROM account_balance_histories WHERE decription='Buy Point Card' AND credit>0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function summaryupgrade(){
        global $conn;
        $sql ="SELECT SUM(credit) AS totalupgrade FROM account_balance_histories WHERE decription LIKE '%Upgrade to%' AND credit>0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function summarywithdraw(){
        global $conn;
        $sql ="SELECT SUM(amount) AS totalwithdraw FROM withdraw WHERE status=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function summarymemberbalance(){
        global $conn;
        $sql ="SELECT SUM(balance) AS totalbalance FROM account_balance";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function summaryrewardbalance(){
        global $conn;
        $sql ="SELECT SUM(remaining_balance) AS totalreward FROM account_balance_bonus";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function getdatamemberdashboard($start_date='',$end_date=''){
        global $conn;
        if($start_date=='' && $end_date==''){
            $sql="SELECT 
                        CAST(created_date AS date) AS registration_date,
                        COUNT(*) AS member_count
                    FROM public.member
                    GROUP BY 
                        CAST(created_date AS date)
                    ORDER BY 
                        registration_date DESC 
                    LIMIT 31
                    ";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($data as $row){
            $dt []= array(
                'x'=>$row['registration_date'],
                'y'=>$row['member_count']
            );
        }
        return $dt;  
    }

    function checkuserstart($star){
        global $conn;
        $sql ="SELECT COUNT(*) AS totalmember FROM member WHERE user_star=:star";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':star', $star, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function totaldeposit($user_id){
        global $conn;
        $sql="SELECT SUM(amount) AS totaldeposit FROM account_deposit WHERE user_id=:user_id AND (status=2 OR status=1)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function totalbuyenergy($user_id){
        global $conn;
        $sql="SELECT SUM(credit) AS totalbuyenergy FROM account_balance_histories WHERE user_id=:user_id AND decription LIKE '%Buy Point Card%' AND credit>0";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function totalwithdraw($user_id){
        global $conn;
        $sql="SELECT SUM(amount) AS totalwithdraw FROM withdraw WHERE user_id=:user_id AND status=1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function balanceuser($user_id){
        global $conn;
        $sql="SELECT * FROM account_balance WHERE user_id=:user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function balancerewarduser($user_id){
        global $conn;
        $sql="SELECT * FROM account_balance_bonus WHERE user_id=:user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function totalteams($user_id){
        global $conn;
        $sql="SELECT count(a.*) AS totalteams, SUM(b.personaltrade) AS teamstrade FROM networks a JOIN member b ON a.member_id=b.id WHERE a.upline_id=:user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function teamturnover($user_id){
        global $conn;
        $sql="SELECT SUM(b.credit) AS totalturnover FROM networks a JOIN account_balance_histories b ON b.user_id=a.member_id WHERE a.upline_id=:user_id AND b.credit>0 AND b.decription LIKE '%Buy Point Card%'";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    
    function _updatebalance($usd_balance,$energy_balance,$user_id){
        global $conn;
        $sql="UPDATE account_balance SET balance='$usd_balance', pointcard_balance='$energy_balance' WHERE user_id=:user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        return $stmt->execute();
    }

    function getbalanceuser($user_id){
        global $conn;
        $sql="SELECT * FROM account_balance WHERE user_id=:user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function getmemberdataactive($start_date,$end_date){
        global $conn;
        $sql="SELECT * FROM member WHERE created_date>='$start_date' AND created_date<='$end_date' AND user_star=1 ORDER BY created_date";
        $stmt = $conn->prepare($sql);
        //$stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    function getbuypointcard($start_date,$end_date){
        global $conn;
        $sql="SELECT a.credit,a.created_date,b.username FROM account_balance_histories a JOIN member b ON a.user_id=b.id WHERE a.decription='Buy Point Card' AND a.credit>0 AND created_date>='$start_date' AND created_date<='$start_date' ORDER BY created_date";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    function checkusername($username){
        global $conn;
        $sql="SELECT * FROM member WHERE username='$username'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function getaccountdepositdashboard($limit=10){
        global $conn;
        $sql="SELECT * FROM account_deposit ORDER BY created_date DESC limit 10";
    }
?>