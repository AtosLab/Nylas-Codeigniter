            <div class="page-content-wrapper">

                <!-- HEADER -->
                <div class="page-header d-flex flex-column justify-content-center light-fg">

                    <div class="search-bar row align-items-center no-gutters bg-white text-auto">

                        <button type="button" class="sidebar-toggle-button btn btn-icon d-block d-lg-none" data-fuse-bar-toggle="mail-sidebar">
                            <i class="icon icon-menu"></i>
                        </button>

                        <i class="icon-magnify s-6 mx-4"></i>

                        <input class="search-bar-input col" type="text" placeholder="Search for an e-mail or contact">

                    </div>
                </div>
                <!-- / HEADER -->

                <div class="page-content-card">
                    <!-- CONTENT -->
                    <!-- CONTENT TOOLBAR -->
                    <div class="toolbar row no-gutters align-items-center px-4 px-sm-6">

                        <div class="col">

                            <div class="row no-gutters align-items-center">

                                <div class="col-auto">

                                    <button type="button" class="btn btn-icon" aria-label="archive">
                                        <i class="icon-keyboard-backspace"></i>
                                    </button>

                                </div>

                                <div class="action-buttons col">

                                    <div class="row no-gutters align-items-center flex-nowrap d-none d-xl-flex">

                                        <div class="divider-vertical"></div>

                                        <button type="button" class="btn btn-icon" aria-label="archive">
                                            <i class="icon icon-archive"></i>
                                        </button>

                                        <button type="button" class="btn btn-icon" aria-label="spam">
                                            <i class="icon icon-alert-octagon"></i>
                                        </button>

                                        <button type="button" class="btn btn-icon" aria-label="delete" onclick="delete_msg('<? echo $id; ?>')">
                                            <i class="icon icon-delete"></i>
                                        </button>

                                        <div class="divider-vertical"></div>

                                        <button type="button" class="btn btn-icon" aria-label="move to">
                                            <i class="icon icon-folder"></i>
                                        </button>

                                        <button type="button" class="btn btn-icon" aria-label="labels">
                                            <i class="icon icon-label"></i>
                                        </button>

                                        <button type="button" class="btn btn-icon" aria-label="move to">
                                            <i class="icon icon-folder"></i>
                                        </button>

                                        <div class="divider-vertical"></div>

                                        <button type="button" class="btn btn-icon" aria-label="more">
                                            <i class="icon icon-dots-vertical"></i>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-auto">

                            <div class="row no-gutters align-items-center">

                                <span class="page-info px-2 d-none d-sm-block"></span>

                                <button type="button" class="btn btn-icon">
                                    <i class="icon icon-settings"></i>
                                </button>

                            </div>
                        </div>
                    </div>

                    <!-- / CONTENT TOOLBAR -->
                    <div class="page-content custom-scrollbar" style="min-height: 300px;">
                        <div class="thread-list w-100">
                        	<div class="thread row no-gutters flex-wrap flex-sm-nowrap align-items-center py-2 px-3 py-sm-4 px-sm-6 " style="cursor: default;">
                                <div class="info col px-4">
                                    <div class="name row no-gutters align-items-center">

                                        <div class="avatar mr-2">
                                            
                                        </div>

                                        <div style="color: #555;">
	                                        <p style="margin: 0px;"><strong style="color: #000;"><? echo $msg['from'][0]['name']; ?></strong>&#8810;<? echo $msg['from'][0]['email']; ?>&#8811;</p>
	                                        <p style="margin: 0px;">
	                                        	<div class="user-menu-button dropdown">
						                            <div class="dropdown-toggle ripple row align-items-center no-gutters px-2 px-sm-4" role="button" id="dropdownUserMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding-left: 0px !important;">
						                                <span class="username mx-3 d-none d-md-block" style="margin-left: 0px !important;">
						                                	<?
				                                        		if($msg['to'][0]['name'] != '')
				                                        			echo 'to '.$msg['to'][0]['name']; 
				                                        		else
				                                        		{
				                                        			$mail_split = explode("@", $msg['to'][0]['email']);
				                                        			echo 'to '.$mail_split[0];
				                                        		}
				                                        	?>
				                                        </span>
						                            </div>

						                            <div class="dropdown-menu" aria-labelledby="dropdownUserMenu" style="min-width: 500px; padding: 20px;">
						                                <div class="row no-gutters align-items-center flex-nowrap">
					                                        <table class="drop-mail-property">
					                                        	<tr>
					                                        		<td class="left">from:  </td>
					                                        		<td class="right"><strong style="color: #000;"><? echo $msg['from'][0]['name']; ?></strong>&#8810;<? echo $msg['from'][0]['email']; ?>&#8811;</td>
					                                        	</tr>
					                                        	<tr>
					                                        		<td class="left">to:  </td>
					                                        		<td class="right"><? echo $msg['to'][0]['name']; ?>&#8810;<? echo $msg['to'][0]['email']; ?>&#8811;</td>
					                                        	</tr>
					                                        	<tr>
					                                        		<td class="left">date:  </td>
					                                        		<td class="right"><? echo gmdate("M d", $msg['date']); ?></td>
					                                        	</tr>
					                                        	<tr>
					                                        		<td class="left">subject:  </td>
					                                        		<td class="right"><? echo $msg['subject']; ?></td>
					                                        	</tr>
					                                        	<tr>
					                                        		<td class="left">mailed-by:  </td>
					                                        		<td class="right"><? $mail_split = explode("@", $msg['from'][0]['email']); echo $mail_split[1]; ?></td>
					                                        	</tr>
					                                        </table>
					                                    </div>
						                            </div>
						                        </div>
	                                        </p>
	                                    </div>
                                    </div>
                                   
                                </div>                                
                            </div>

                        	<div class="row no-gutters flex-wrap flex-sm-nowrap align-items-center py-2 px-3 py-sm-4 px-sm-6 ">
                        		<div class="info col px-4">
                                    <div class="name row no-gutters align-items-center">
                                        <? echo $msg['body']; ?>
                                    </div>
                                   
                                </div>
                        		
                        	</div>
                        </div>
                    </div>

                    <!-- / CONTENT -->
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function delete_msg(msg_id)
    {
        var url = "<? echo site_url().'index.php/nylas_mail/move_trash/'; ?>" + msg_id;
        location.href= url;
    }
    
</script>