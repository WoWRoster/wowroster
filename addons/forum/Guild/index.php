<?php

include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;

	//$view->extend('forum/forum_template', 'content', array('title' => $view->lang->get('forum')->get('forums'))); 
	//
	$forums = $functions->getForums();

	foreach($forums as $id =>$forum)
	{
		$roster->tpl->assign_block_vars('forums', array(
					'FORUM_ID' 	=> $forum['forumid'],
					'FORUM_URL'	=> makelink('guild-'.$addon['basename'].'-forum&amp;id=' . $forum['forumid']),
					'TITLE'		=> $forum['title'],
					'POSTS'		=> $forum['posts'],
					'POSTER'	=> $forum['t_poster'],
					'P_TITLE'	=> $forum['t_title'],
					'P_URL'		=> makelink('guild-'.$addon['basename'].'-topic&amp;tid=' . $forum['t_id']),
					'DESC'		=> $forum['desc']
				));
	}		
	
	$roster->tpl->set_filenames(array(
		'forum_main' => $addon['basename'] . '/index.html',
		));

	$roster->tpl->display('forum_main');
	
	/*


<div class="forums">

<div class="col c12">
	<div class="row">
		<div class="col c6">
			<h2><?php ##echo $view->lang->get('forum')->get('forum') ?></h2>
		</div>
		<div class="col c2">
			<h2><?php ##echo $view->lang->get('forum')->get('posts')?></h2>
		</div>
		<div class="col c4">
			<h2><?php ##echo $view->lang->get('forum')->get('last_post')?></h2>
		</div>
	</div>	
</div>
<?php foreach($forums as $id =>$forum):?>
<div class="col c12">
	<div class="row">
		<div class="col c6">
			<a href="<?php ##echo $view->url->linkTo('Forum','forumShow', array('page' => 0, 'title'=>$forum->getUrl()))?>"><h4><?php echo $forum['title'];?></h4></a>
			<p><?php echo $forum['desc'];?></p>
		</div>
		<div class="col c2">
			<p>
			Threads : <?php echo $forum['posts'];?><br />
			<?php #echo $view->lang->get('forum')->get('posts')?>: <?php #echo $forum->getPostCount();?><br />
			</p>
		</div>
		<div class="col c4">
			<?php #$lt = $forum->getLastThread(); /* $li = Get the first thread/?>
			<?php #if ($lt): ?>
			<?php #$fp = $lt->getFirstPost(); /* $fp = First post get /?>
			<a href="<?php #echo $view->url->linkTo('Forum','thread', array('title'=>$forum->getUrl(), 'id' => $lt->getID(),'page' => 0 ))?>"><h4><?php #echo $lt->getTitle()?></h4></a>
			<p>
			by <?php #echo $fp->getUsername()?><br />
			<?php #echo $fp->getPostedSince()?> ago
			</p>
			<?php 
		</div>
	</div>
</div>

	
	*/
?>

</div>