<?php
	echo $html->css('wmd.css');
	echo $html->script('wmd/showdown.js');
	echo $html->script('wmd/wmd.js');

	echo $html->script('jquery/jquery.js');
	echo $html->script('jquery/jquery.bgiframe.min.js');
	echo $html->script('jquery/jquery.ajaxQueue.js');
	echo $html->script('jquery/thickbox-compressed.js');
	echo $html->script('jquery/jquery.autocomplete.js');
	echo $html->script('/tags/suggest');

	echo $html->css('thickbox.css');
	echo $html->css('jquery.autocomplete.css');
?>


  <script>
  $(document).ready(function(){
	$("#resultsContainer").show("blind");

	$("#tag_input").autocomplete(tags, {
		minChars: 0,
		multiple: true,
		width: 350,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max) {
			return row.name + " (<strong>" + row.count + "</strong>)";
		},
		formatMatch: function(row, i, max) {
			return row.name + " " + row.count;
		},
		formatResult: function(row) {
			return row.name;
		}
	});

	$("#PostTitle").blur(function(){
		if($("#PostTitle").val().length >= 10) {
			$("#title_status").toggle();
			getResults();
		} else {
			$("#title_status").show();
		}
	});

	function getResults()
	{

		$.get("/mini_search",{query: $("#PostTitle").val(), type: "results"}, function(data){

			$("#resultsContainer").html(data);
			$("#resultsContainer").show("blind");
		});
	}

	$("#PostTitle").keyup(function(event){
		if($("#PostTitle").val().length < 10) {
			$("#title_status").html('<span class="red"><?php echo __('Titles must be at least 10 characters long.',true) ?></span>');
		} else {
			$("#title_status").html('<?php echo __('What is your question about?',true) ?>');
		}
	});

  });
  </script>
<h2><?php echo __('Ask a question',true) ?></h2>
<?php if ($session->read('errors')) {
		foreach($session->read('errors.errors') as $error) {
			echo '<div class="error">' . $error . '</div>';
		}
	}
?>
<?=$form->create('Post', array('action' => 'ask'));?>
<?=$form->label(__('Title',true));?><br/>

<?=$form->text('title', array('class' => 'wmd-panel big_input', 'value' => $session->read('errors.data.Post.title')));?><br/>
<span id="title_status"class="quiet"><?php echo __('What is your question about?',true) ?></span>
<div id="resultsContainer"></div>

<?=$form->label(__('Category',true));?><br/>
<?=$form->select('category', array('' => '-- category --', 'Academic writing' => 'Academic writing', 'Research' => 'Research', 'Scholarly publishing' => 'Scholarly publishing'), array('class' => 'your-mom'));?><br/>

<div id="wmd-button-bar" class="wmd-panel"></div>
<?=$form->textarea('content', array(
	'id' => 'wmd-input', 'class' => 'wmd-panel', 'value' => $session->read('errors.data.Post.content')
	));
 ?>

<div id="wmd-preview" class="wmd-panel"></div>

<?=$form->label(__('Tags',true));?><br/>
<?=$form->text('tags', array('id' => 'tag_input', 'class' => 'wmd-panel big_input'));?><br/>
<span id="tag_status" class="quiet"><?php echo __('Combine multiple words into single-words.',true) ?></span>

<?php if(!$session->check('Auth.User.id')) { ?>
<h2><?php echo __('Who Are You?',true) ?></h2>
<span class="quiet"><?php echo __('Have an account already?',true) ?> <a href="#"><?php echo __('Login before answering!',true) ?></a></span><br/>
	<?=$form->label(__('Name',true));?><br/>
	<?=$form->text('User.username', array(
		'class' => 'big_input medium_input',
		'value' => $session->read('errors.data.User.username')
		));
	?><br/>
	<?=$form->label(__('Email',true));?><br/>
	<?=$form->text('User.email', array(
		'class' => 'big_input medium_input',
		'value' => $session->read('errors.data.User.email')
		));
	?><br/>
<?php } ?>
<br/><br/>
<?=$form->checkbox('Post.notify', array('checked' => true));?>
<span style="margin-left: 5px;"><?php echo __('Notify me when my question is answered.',true) ?></span>

<?=$form->end( __('Ask a question',true));?>

