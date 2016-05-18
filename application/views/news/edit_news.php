 <div class="container">

   
 <h3><?php echo $title;?></h3>
   
 

  <div class="row">
     <form method="post" action="<?php echo site_url('news/update_news/'.$news['quid']);?>">
	
<div class="col-md-12">
<br> 
 <div class="login-panel panel panel-default">
		<div class="panel-body"> 
	
	
	
			<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		?>	
		
		
		 			<div class="form-group">	 
					<label for="inputEmail" class="sr-only"><?php echo $this->lang->line('title');?></label> 
					<input type="text"  name="title"  value="<?php echo $news['title'];?>" class="form-control" placeholder="<?php echo $this->lang->line('title');?>"  required autofocus>
			</div>
				<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('content');?></label> 
					<textarea   name="content"  class="form-control tinymce_textarea" ><?php echo $news['content'];?></textarea>
			</div>
	<button class="btn btn-success" type="submit"><?php echo $this->lang->line('submit');?></button>
 
		</div>
</div>

</div>
      </form>
</div>
</div>