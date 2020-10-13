<?php
class News {

    // подключение к базе данных и таблице 'newss' 
    private $conn;
    private $table_name = "news";

    // свойства объекта 
    public $id;
    public $title;
    public $description;
    public $link;
    public $time;

    // конструктор для соединения с базой данных 
    public function __construct($db){
        $this->conn = $db;
    }

    // метод read() - получение товаров 
    function read(){

      // выбираем все записи 
      $query = "SELECT * FROM " . $this->table_name . " ORDER BY `time` DESC";
        
    
      // подготовка запроса 
      $stmt = $this->conn->prepare($query);

      // выполняем запрос 
      $stmt->execute();
      return $stmt;
    }

    // метод create - создание товаров 
    function create(){

      // запрос для вставки (создания) записей 
      $query = "INSERT INTO
                  " . $this->table_name . "
              SET
                  title = :title, link = :link, description = :description, img_link = :img_link, time = :time";

      // подготовка запроса 
      $stmt = $this->conn->prepare($query);
      // очистка 
      $this->title=htmlspecialchars(strip_tags($this->title));
      $this->link=htmlspecialchars(strip_tags($this->link));
      $this->description=htmlspecialchars(strip_tags($this->description));
      $this->time=htmlspecialchars(strip_tags($this->time));
 
      // привязка значений 
      $stmt->bindParam(":title", $this->title);
      $stmt->bindParam(":link", $this->link);
      $stmt->bindParam(":description", $this->description); 
      $stmt->bindParam(":time", $this->time);

      // выполняем запрос 
      if ($stmt->execute()) {
          return true;
      }
      
      return false;
    }


    // используется при заполнении формы обновления товара 
    function readOne() {

        // запрос для чтения одной записи (товара) 
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                     
                WHERE
                    p.id = ?
                LIMIT
                    0,1";

        // подготовка запроса 
        $stmt = $this->conn->prepare( $query );

        // привязываем id товара, который будет обновлен 
        $stmt->bindParam(1, $this->id);

        // выполняем запрос 
        $stmt->execute();

        // получаем извлеченную строку 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // установим значения свойств объекта 
        $this->title = $row['title'];
        $this->link = $row['link'];
        $this->description = $row['description'];
        $this->time = $row['time'];
    }


    // метод update() - обновление товара 
    function update(){

        // запрос для обновления записи (товара) 
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    title = :title,
                    link = :link,
                    description = :description,
                    time = :time
                WHERE
                    id = :id";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->link=htmlspecialchars(strip_tags($this->link));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // привязываем значения 
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':link', $this->link);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);

        // выполняем запрос 
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // метод delete - удаление товара 
    function delete(){

        // запрос для удаления записи (товара) 
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $this->id=htmlspecialchars(strip_tags($this->id));

        // привязываем id записи для удаления 
        $stmt->bindParam(1, $this->id);

        // выполняем запрос 
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // метод search - поиск товаров 
    function search($keywords){

        // выборка по всем записям 
        $query = "SELECT
                   *
                FROM
                    " . $this->table_name . " 
                   
                WHERE
                    title LIKE ? OR description LIKE ?
                ORDER BY
                    create DESC";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // привязка 
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        // выполняем запрос 
        $stmt->execute();

        return $stmt;
    }
}
?>