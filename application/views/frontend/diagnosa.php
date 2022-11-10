<?php include 'komponen/header.php'?>

<br>
<br>

<section id="home" class="">
    <div class="container" style="padding-top: -200px;">
        <div class="row">
            <div class="main_home">

                <!-- Main content -->
                <div class="col-md-offset-1 col-md-10">
                    <?php $this->load->view($view)?>
                </div>

            </div>

        </div>
        <!--End off row-->
    </div>
    <!--End off container -->
</section>
<!--End off Home Sections-->

<?php include 'komponen/footer.php'?>