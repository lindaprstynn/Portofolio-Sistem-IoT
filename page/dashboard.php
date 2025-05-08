<?php
$sql = "SELECT * FROM devices WHERE active = 'Yes'";
$result = mysqli_query($conn, $sql)
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><span id="suhu">-</span> C</h3>

              <p>Suhu</p>
            </div>
            <div class="icon">
              <i class="fas fa-temperature-high"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="small-box bg-indigo">
            <div class="inner">
              <h3><span id="kelembaban">-</span> %</h3>

              <p>Kelembaban</p>
            </div>
            <div class="icon">
              <i class="fas fa-water"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="small-box bg-maroon">
            <div class="inner">
              <h3 id="potensiometer">-</h3>

              <p>Potensiometer</p>
            </div>
            <div class="icon">
              <i class="fas fa-tachometer-alt"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- <div class="col-6">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Servo</h3>
          </div>
          <!- /.card-header ->
          <div class="card-body">
            <div class="row margin">
              <div class="col-sm-12">
                <input id="servo" onchange="publishServo(this)" type="text">
              </div>
            </div>
          </div>
          <!- /.card-body ->
        </div>
        <!- /.card ->
      </div> -->
      <div class="col-6">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Lampu</h3>
          </div>
          <div class="card-body table-responsive pad">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-primary" id="label-lampuNyala">
                <input type="radio" name="lampu" onchange="publishLampu(this)" id="lampuNyala" autocomplete="off"> Nyala
              </label>
              <label class="btn btn-secondary" id="label-lampuMati">
                <input type="radio" name="lampu" onchange="publishLampu(this)" id="lampuMati" autocomplete="off"> Mati
              </label>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
      <div class="col-6">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Buzzer</h3>
          </div>
          <div class="card-body table-responsive pad">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-primary" id="label-buzzerNyala">
                <input type="radio" name="buzzer" onchange="publishBuzzer(this)" id="buzzerNyala" autocomplete="off"> Nyala
              </label>
              <label class="btn btn-secondary" id="label-buzzerNyala">
                <input type="radio" name="buzzer" onchange="publishBuzzer(this)" id="buzzerMati" autocomplete="off"> Mati
              </label>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card card-indigo">
        <div class="card-header">
          <h3 class="card-title">Status Perangkat</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0" style="height: 300px;">
          <table class="table table-head-fixed text-nowrap">
            <thead>
              <tr>
                <th>Serial Number</th>
                <th>Lokasi</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                  <td><?php echo $row['serial_number'] ?></td>
                  <td><?php echo $row['location'] ?></td>
                  <td style="color: red" id="kelasiot/status/<?php echo $row['serial_number'] ?>">Offline</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col-md-6 -->
  </div>
  <!-- /.row -->
</div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>

<script>
  const clientId = Math.random().toString(16).substr(2, 8);
  const host = 'wss://kelasiot-linda.cloud.shiftr.io:443';

  const options = {
    keepalive: 30,
    clientId: clientId,
    username: "kelasiot-linda",
    password: "KvL7mRrqW5NQLf0S",
    protocolId: 'MQTT',
    protocolVersion: 4,
    clean: true,
    reconnectPeriod: 1000,
    connectTimeout: 30 * 1000,
  };

  console.log("Menghubungkan ke Broker");
  const client = mqtt.connect(host, options);

  client.on("connect", () => {
    console.log("Terhubung");
    document.getElementById("status").innerHTML = "Terhubung";
    document.getElementById("status").style.color = "blue";

    client.subscribe("kelasiot/#", {
      qos: 1
    });
  });

  client.on("message", function(topic, payload) {
    if (topic === "kelasiot/12345678/suhu") {
      document.getElementById("suhu").innerHTML = payload;
    } else if (topic === "kelasiot/12345678/kelembaban") {
      document.getElementById("kelembaban").innerHTML = payload;
    } else if (topic === "kelasiot/12345678/potensiometer") {
      document.getElementById("potensiometer").innerHTML = payload;
      // } else if (topic === "kelasiot/12345678/servo") {
      //   let servoSatu = $("#servo").data("ionRangeSlider")

      //   servoSatu.update({
      //     from: payload.toString()
      //   })
    } else if (topic === "kelasiot/12345678/led") {
      if (payload == "on") {
        document.getElementById("label-lampuNyala").classList.add("active");
        document.getElementById("label-lampuMati").classList.remove("active");
      } else {
        document.getElementById("label-lampuNyala").classList.remove("active");
        document.getElementById("label-lampuMati").classList.add("active");
      }
    } else if (topic === "kelasiot/12345678/buzzer") {
      if (payload == "on") {
        document.getElementById("label-buzzerNyala").classList.add("active");
        document.getElementById("label-buzzerMati").classList.remove("active");
      } else {
        document.getElementById("label-buzzerNyala").classList.remove("active");
        document.getElementById("label-buzzerMati").classList.add("active");
      }
    }

    if (topic.includes("kelasiot/status/")) {
      document.getElementById(topic).innerHTML = payload;

      if (payload.toString() === "Offline") {
        document.getElementById(topic).style.color = "red";
      } else if (payload.toString() === "Online") {
        document.getElementById(topic).style.color = "blue";
      }
    }
  });

  // function publishServo(value) {
  //   data = document.getElementById("servo").value;
  //   client.publish("kelasiot/12345678/servo", data, {
  //     qos: 1,
  //     retain: true
  //   });
  // }

  function publishLampu(value) {
    if (document.getElementById("lampuNyala").checked) {
      data = "on";
    }

    if (document.getElementById("lampuMati").checked) {
      data = "off";
    }

    client.publish("kelasiot/12345678/led", data, {
      qos: 1,
      retain: true
    });

  }

  function publishBuzzer(value) {
    if (document.getElementById("buzzerNyala").checked) {
      data = "on";
    }

    if (document.getElementById("buzzerMati").checked) {
      data = "off";
    }

    client.publish("kelasiot/12345678/buzzer", data, {
      qos: 1,
      retain: true
    });

  }
</script>