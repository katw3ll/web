<?php
  
  $list = json_decode(file_get_contents('data.json'),TRUE); 


  if(isset($_POST['surname']) && isset($_POST['firstname']) && isset($_POST['patronymic']) && isset($_POST['year_of_birth']) && isset($_POST['year_of_issue'])){  
    if (!empty($list)){
      $id = array_key_last($list) + 1;
    } else{
      $id = 0;
    }
    $list[$id] = array(
      "id" => $id,
      "surname" => $_POST['surname'],
      "firstname" => $_POST['firstname'],
      "patronymic" => $_POST['patronymic'],
      "hostel" => $_POST['hostel'],
      "year_of_birth" => $_POST['year_of_birth'],
      "year_of_issue" => $_POST['year_of_issue'],
      "marks" => array()
    );
    file_put_contents('data.json',json_encode($list));
  }


  if(isset($_POST['student'])  && isset($_POST['subject']) && !empty($_POST['subject']) && isset($_POST['mark']) && isset($_POST['semester'])){  
    $id = $_POST['student'];
    $semester = $_POST['semester'];
    $list[$id]['marks'][$semester][] = array(
      "subject" => $_POST['subject'],
      "mark" => $_POST['mark']
    );
    file_put_contents('data.json',json_encode($list));
  }


  if (isset($_POST['delete']))
  {
    $new_list = array();
    foreach ($list as $item) {
      if($item['hostel'] == "0"){
        $sum = 0;
        $count = 0;
        if(isset($item['marks']["1"])){
          foreach ($item['marks']["1"] as $i){
            $sum += (int)$i['mark'];
            $count++;
          }
        }
        
        if(isset($item['marks']["2"])){
          foreach ($item['marks']["2"] as $i){
            $sum += (int)$i['mark'];
            $count++;
          }
        }
        if($count == 0 || $sum/$count <= 4.5)
        {
          $new_list[] = $item;
        }
      }
    }
    // echo "<pre>";
    // print_r($new_list);
    // echo "</pre>";
    file_put_contents('data.json',json_encode($new_list));
    $list = $new_list;
  }

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Ученики</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="del">
    <form action="#" method="POST">
      <input type="submit" name="delete" class="btn" value="Задание" />
    </form>
  </div>
  <div class="wrapper">
    <div class="container">
      <div class="card"></div>
      <div class="card">
        <h1 class="title">Карточка студента</h1>
        <form method="POST">
          <div class="input-container">
            <input type="text" id="surname" name="surname" required/>
            <label for="surname">Фамилия</label>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <input type="text" id="firstname" name="firstname" required/>
            <label for="firstname">Имя</label>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <input type="text" id="patronymic" name="patronymic" required/>
            <label for="patronymic">Отчество</label>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <input type="number" id="year_of_birth" name="year_of_birth" required/>
            <label for="year_of_birth">Год рождения</label>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <input type="number" id="year_of_issue" name="year_of_issue" required/>
            <label for="year_of_issue">Год выпуска</label>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <select name="hostel" required>
              <option value="1">Нужно общежитие</option>
              <option value="0">Не нужно общежитие</option>
            </select>
            <div class="bar"></div>
          </div>
          <div class="button-container">
            <button><span>Добавить</span></button>
          </div>
        </form>
      </div>
    </div>
    <div class="container">
      <div class="card"></div>
      <div class="card">
        <h1 class="title">Добавить оценку</h1>
        <form action="" method="POST">
          <div class="input-container">
            <select name="student">
              <option>Выбрать ученика</option>
              <?php
                foreach ($list as $v) {
                  echo '<option value="'.$v['id'].'">'.$v['surname'].' '.$v['firstname'].' '.$v['patronymic'].'</option>';
                }
              ?>
            </select>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <select name="semester" required>
              <option disabled>Семестр</option>
              <option value="1">Сем.1</option>
              <option value="2">Сем.2</option>
            </select>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <input type="text" id="subject" name="subject" required/>
            <label for="subject">Предмет</label>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <select name="mark" required>
              <option disabled>Оценка</option>
              <option value=5>Отл.</option>
              <option value=4>Хорошо</option>
              <option value=3>Удов.</option>
              <option value=2>Неуд.</option>
            </select>
            <div class="bar"></div>
          </div>
          <div class="button-container">
            <button><span>Добавить</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="wrapper">
    <div class="table">
      <div class="card"></div>
      <div class="card">
      <h1 class="title">Содержимое по заданию</h1>
        <table>
          <tr>
            <th>ФИО</th>
            <th>Нужно ли общежитие?</th>
            <th>Год рождения</th>
            <th>Год выпуска</th>
            <th>Оценки за <br>первый семестр</th>
            <th>Оценки за <br>второй семестр</th>
          </tr>
          
          <?php
            $txt = "";
            foreach ($list as $v) {
            if($v['hostel'] == "0"){
              $txt .= "<tr>";
              $txt .= "<td>".$v['surname'].' '.$v['firstname'].' '.$v['patronymic']."</td>";
              $txt .= "<td>".(($v['hostel'] == "1") ? "Да" : "Нет")."</td>";
              $txt .= "<td>".$v['year_of_birth']."</td>";
              $txt .= "<td>".$v['year_of_issue']."</td>";
              
              $txt .= "<td>";
              if(!empty($v['marks']["1"])){
                foreach ($v['marks']["1"] as $i) {
                  switch ($i['mark']) {
                    case "5":
                        $mark = "Отл.";
                        break;
                    case "4":
                        $mark = "Хор.";
                        break;
                    case "3":
                        $mark = "Удов.";
                        break;
                    case "2":
                        $mark = "Неуд.";
                        break;
                  }
                  $txt .= $i['subject']." - ".$mark;
                  $txt .= "<br>";
                }
              }
              
              $txt.= "</td>";
              $txt .= "<td>";
              if(!empty($v['marks']["2"])){
                foreach ($v['marks']["2"] as $i) {
                  switch ($i['mark']) {
                    case "5":
                        $mark = "Отл.";
                        break;
                    case "4":
                        $mark = "Хор.";
                        break;
                    case "3":
                        $mark = "Удов.";
                        break;
                    case "2":
                        $mark = "Неуд.";
                        break;
                  }
                  $txt .= $i['subject']." - ".$mark;
                  $txt .= "<br>";
                }
              }
              
              $txt.= "</td>";
              
              $txt .= "</tr>";

            }}
            echo $txt;
          ?>


        </table>
      </div>
    </div>
  </div>
  <div class="wrapper">
    <div class="table">
      <div class="card"></div>
      <div class="card">

        <h1 class="title">Вывод всего файла</h1>
        <table>
          <tr>
            <th>ФИО</th>
            <th>Нужно ли общежитие?</th>
            <th>Год рождения</th>
            <th>Год выпуска</th>
            <th>Оценки за <br>первый семестр</th>
            <th>Оценки за <br>второй семестр</th>
          </tr>
          
          <?php
            $txt = "";

            foreach ($list as $v) {
              $txt .= "<tr>";
              $txt .= "<td>".$v['surname'].' '.$v['firstname'].' '.$v['patronymic']."</td>";
              $txt .= "<td>".(($v['hostel'] == "1") ? "Да" : "Нет")."</td>";
              $txt .= "<td>".$v['year_of_birth']."</td>";
              $txt .= "<td>".$v['year_of_issue']."</td>";
              
              $txt .= "<td>";
              if(!empty($v['marks']["1"])){
                foreach ($v['marks']["1"] as $i) {
                  switch ($i['mark']) {
                    case "5":
                        $mark = "Отл.";
                        break;
                    case "4":
                        $mark = "Хор.";
                        break;
                    case "3":
                        $mark = "Удов.";
                        break;
                    case "2":
                        $mark = "Неуд.";
                        break;
                  }
                  $txt .= $i['subject']." - ".$mark;
                  $txt .= "<br>";
                }
              }
              
              $txt.= "</td>";
              $txt .= "<td>";
              if(!empty($v['marks']["2"])){
                foreach ($v['marks']["2"] as $i) {
                  switch ($i['mark']) {
                    case "5":
                        $mark = "Отл.";
                        break;
                    case "4":
                        $mark = "Хор.";
                        break;
                    case "3":
                        $mark = "Удов.";
                        break;
                    case "2":
                        $mark = "Неуд.";
                        break;
                  }
                  $txt .= $i['subject']." - ".$mark;
                  $txt .= "<br>";
                }
              }
              
              $txt.= "</td>";
              
              $txt .= "</tr>";

            }
            echo $txt;
          ?>


        </table>
      </div>
    </div>
  </div>
  
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/index.js"></script>
</body>
</html>
<?php unset($_POST); ?>