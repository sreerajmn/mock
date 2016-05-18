<div class="container">


    <h3><?php echo $title; ?></h3>



    <div class="row">

        <div class="col-md-12">
            <br> 
            <div class="login-panel panel panel-default">
                <div class="panel-body"> 



                    <?php
                    if ($this->session->flashdata('message')) {
                        echo $this->session->flashdata('message');
                    }
                    ?>	
                    <table class="table table-bordered">
                        <tr><td><?php echo $this->lang->line('title'); ?></td><td><?php echo $news['title']; ?></td></tr>
                        <tr><td colspan='2'><?php echo $this->lang->line('content'); ?><br><?php echo $news['content']; ?></td></tr>
                        <tr><td><?php echo $this->lang->line('date'); ?></td><td><?php echo date('Y-m-d H:i:s', $news['created_at']); ?></td></tr>
                    </table>

                </div>
            </div>




        </div>
    </div>





</div>
