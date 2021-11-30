<?php
$minimum_range = 0;
$maximum_range = 1;

include("config.php");
/** @var Connection $connection */
$connection = require_once 'connection.php';
// Read notes from database
$notes = $connection->getNotes();

$currentNote = [
    'id' => '',
    'title' => '',
    'description' => ''
];
if (isset($_GET['id'])) {
    $currentNote = $connection->getNoteById($_GET['id']);
}

$currentDate = [
  'date' => ''
];

if (!isset($_GET['id']) == '') {

  if (!$currentNote['title'] == 0) {
    $maximum_range = $currentNote['title'];
    $minimum_range = $maximum_range-1;
  }

}


$fetchVideos = mysqli_query($con, "SELECT * FROM videos ORDER BY id DESC");
while($row = mysqli_fetch_assoc($fetchVideos)){
  $length = $row['length'];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="style-biologists.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,900;1,100;1,300&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

        <script src="https://kit.fontawesome.com/26ce6e3e48.js" crossorigin="anonymous"></script>


        <title>Notes</title>
    </head>
    <body id="body-pd">
        <header class="header" id="header">
            <div class="header-toggle">
                <i class="fas fa-bars" id="header-toggle"></i>
            </div>
            <ul class="nav-horizontal">
              <li><a href="#home">Help</a></li>
              <li><a href="index.php">Quit</a></li>
            </ul>
        </header>
        <div class="side-navbar" id="nav-bar">
            <nav class="nav">
                <div>
                    <a href="#" class="nav-logo">
                        <i class='fas fa-circle'></i>
                        <span class="nav-logo-name">LimfjordCam</span>
                    </a>

                    <div class="nav-list">
                        <a href="biologists-video.php" class="nav-link">
                        <i class='fas fa-play-circle' ></i>
                            <span class="nav-name">Video</span>
                        </a>

                        <a href="biologists-statistics.php" class="nav-link">
                            <i class='fas fa-chart-bar' ></i>
                            <span class="nav-name">Statistics</span>
                        </a>

                        <a href="#" class="nav-link active">
                            <i class='fas fa-sticky-note' ></i>
                            <span class="nav-name">Notes</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="wrapper-notes-tab" id="notes-tab">
          <div>
            <div class="date-picker">
              <label for="dateOfNote">Notes by date</label>
              <input type="date" name="dateofNote" id="notePicker" value="<?php echo $date?>">
            </div>
          </div>

          <div class="get-notes">
            <p>Retrieve notes by dragging the handles on the slider. Click on the note title or description to edit.</p>

       <div class="filter-container">
         <div class="slider-left">
           <input type="text" readonly name="minimum_range" id="minimum_range" class="form-control" value="<?php echo $minimum_range;?>" />
         </div>
         <div id="time_range"></div>
         <div class="slider-right">
           <input type="text" readonly name="maximum_range" id="maximum_range" class="form-control" value="<?php echo $maximum_range; ?>" />
         </div>
       </div>

      <div class="notes-from-database">
        <div id="load_product"></div>
      </div>

            <?php if ($currentNote['id']): ?>
              <div class="">
                <form action="create.php" class="note-editor" method="post">
                  <input type="hidden" name="id" value="<?php echo $currentNote['id'] ?>">
                  <span class="top-note-editor"><i class="fas fa-edit"></i>Edit event at: <input type="text" id="timeString", name="title" value="<?php echo $currentNote['title']?>" readonly="readonly" class="timeString-display"></span>
                  <textarea class="#" name="description" rows="8" cols="45" placeholder="Type your notes here"><?php echo $currentNote['description'] ?></textarea>
                    <button>Update note</button>
                </form>
              </div>

                 <?php else: ?>

                 <?php endif ?>

          </div>

        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="biologists-js.js"></script>
        <script src="load.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </body>
</html>

<script>
$(document).ready(function(){

  $( "#time_range" ).slider({



    range: true,
    min: 0,
    max: <?php echo $length; ?>,
    values: [ <?php echo $minimum_range; ?>, <?php echo $maximum_range; ?> ],
    slide:function(event, ui){
      $("#minimum_range").val(ui.values[0]);
      $("#maximum_range").val(ui.values[1]);
      load_product(ui.values[0], ui.values[1]);
    }
  });

  load_product(<?php echo $minimum_range; ?>, <?php echo $maximum_range; ?>);

  $(function () {

  $('form').on('submit', function (e) {

    e.preventDefault();

    $.ajax({
      type: 'post',
      url: 'create.php',
      data: $('form').serialize(),
      success: function () {
        //$('form')[0].reset();
        //alert("Your note has been saved!");
        load_product(<?php echo $minimum_range; ?>, <?php echo $maximum_range; ?>);
      }
    });

  });

  });
  



  function load_product(minimum_range, maximum_range)
  {
    $.ajax({
      url:"fetch.php",
      method:"POST",
      data:{minimum_range:minimum_range, maximum_range:maximum_range},
      success:function(data)
      {
        $('#load_product').fadeIn('slow').html(data);
      }
    });

  }

});

</script>
