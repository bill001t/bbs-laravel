body{
	<?php if($__css['bgcolor']) { ?>
	background-color:<?php echo $__css['bgcolor']?>;
	<?php } ?>
	<?php if($__css['bg']) { ?>
	background-image:url(<?php echo App\Core\Tool::getPath($__css['bg'])?>);
		<?php if($__css['bgalign']) { ?>
		background-position:<?php echo $__css['bgalign']?> 45px;
		<?php } if($__css['bgtile']) { ?>
		background-repeat:<?php echo $__css['bgtile']?>;
		<?php } ?>
	<?php } ?>
	<?php if($__css['size']) { ?>
	font-size:<?php echo $__css['size']?>;
	<?php } if($__css['font']) { ?>
	font-family:<?php echo $__css['font']?>;
	<?php } if($__css['coretext']) { ?>
	color:<?php echo $__css['coretext']?>;
	<?php } ?>
}

<?php if($__css['corelink']) { ?>
a{
	color:<?php echo $__css['corelink']?>;
}
<?php } ?>

<?php if($__css['subjectsize']) { ?>
.main .thread_posts_list .st{
	font-size:<?php echo $__css['subjectsize']?>;
}
<?php } ?>

<?php if($__css['contentsize']) { ?>
div.editor_content{
	font-size:<?php echo $__css['contentsize']?>;
}
<?php } ?>

.header_wrap{
	<?php if($__css['headbgcolor']) { ?>
	background-color:<?php echo $__css['headbgcolor']?>;
	<?php } ?>
	<?php if($__css['headbg']) { ?>
	background-image:url(<?php echo App\Core\Tool::getPath($__css['headbg'])?>);
		<?php if($__css['headbgalign']) { ?>
		background-position:<?php echo $__css['headbgalign']?> 45px;
		<?php } if($__css['headbgtile']) { ?>
		background-repeat:<?php echo $__css['headbgtile']?>;
		<?php } ?>
	<?php } ?>
}

<?php if($__css['headlink']) { ?>
.header_wrap a,
.nav li a{
	color:<?php echo $__css['headlink']?>;
}
<?php } ?>

<?php if($__css['headactivelink']) { ?>
.nav li.current a,
.nav li.current a:hover{
	background:<?php echo $__css['headactivelink']?>;
}
<?php } ?>

<?php if($__css['headactivecolor']) { ?>
.nav li.current a{
	color:<?php echo $__css['headactivecolor']?>;
}
<?php } ?>

.box_wrap{
	<?php if($__css['boxbgcolor']) { ?>
	background-color:<?php echo $__css['boxbgcolor']?>;
	<?php } ?>
	<?php if($__css['boxbg']) { ?>
	background-image:url(<?php echo App\Core\Tool::getPath($__css['boxbg'])?>);
		<?php if($__css['boxbgalign']) { ?>
		background-position:<?php echo $__css['boxbgalign']?> 45px;
		<?php } if($__css['boxbgtile']) { ?>
		background-repeat:<?php echo $__css['boxbgtile']?>;
		<?php } ?>
	<?php } ?>
	
<?php if($__css['boxborder']) { ?>
	border-color:<?php echo $__css['boxborder']?>;
<?php } ?>

<?php if($__css['boxtext']) { ?>
	color:<?php echo $__css['boxtext']?>;
<?php } ?>
}
<?php if($__css['boxlink']) { ?>
.box_wrap a{
	color:<?php echo $__css['boxlink']?>;
}
<?php } ?>

.box_wrap .box_title{
	<?php if($__css['boxhdbgcolor']) { ?>
	background-color:<?php echo $__css['boxhdbgcolor']?>;
	<?php } ?>
	<?php if($__css['boxhdbg']) { ?>
	background-image:url(<?php echo App\Core\Tool::getPath($__css['boxhdbg'])?>);
		<?php if($__css['boxhdbgalign']) { ?>
		background-position:<?php echo $__css['boxhdbgalign']?> 45px;
		<?php } if($__css['boxhdbgtile']) { ?>
		background-repeat:<?php echo $__css['boxhdbgtile']?>;
		<?php } ?>
	<?php } ?>
	
<?php if($__css['boxhdborder']) { ?>
	border-color:<?php echo $__css['boxhdborder']?>;
<?php } ?>

<?php if($__css['boxhdtext']) { ?>
	color:<?php echo $__css['boxhdtext']?>;
<?php } ?>
}

<?php if($__css['boxhdlink']) { ?>
.box_wrap .box_title a{
	color:<?php echo $__css['boxhdlink']?>;
}
<?php } ?>
