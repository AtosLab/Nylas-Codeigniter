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


                                </div>

                                <div class="action-buttons col">
                                    <div class="row no-gutters align-items-center flex-nowrap d-none d-xl-flex">
                                    	<h3>Send new message.</h3>
                                    </div>                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-auto">

                            <div class="row no-gutters align-items-center">


                            </div>
                        </div>
                    </div>

                    <!-- / CONTENT TOOLBAR -->
                    <div class="page-content custom-scrollbar">
                        <div class="thread-list w-100">
                        	<form id="frm_new_msg" method="post">
	                        	<div class="row no-gutters flex-wrap flex-sm-nowrap align-items-center py-2 px-3 py-sm-4 px-sm-6 unread">
	                        		<div class="col-12 col-sm-auto d-flex flex-sm-column justify-content-center align-items-center" style="width: 100%;">
										<div class="input-group">
										    <input id="txt_recipients" name="txt_recipients" type="email" class="form-control" placeholder="Recipients" aria-label="Recipients"
										           aria-describedby="basic-addon1">
										</div>
	                        		</div>
	                        	</div>
	                        	<div class="row no-gutters flex-wrap flex-sm-nowrap align-items-center py-2 px-3 py-sm-4 px-sm-6 unread">
	                        		<div class="col-12 col-sm-auto d-flex flex-sm-column justify-content-center align-items-center" style="width: 100%;">
										<div class="input-group">
										    <input id="txt_subject" name="txt_subject" type="text" class="form-control" placeholder="Subject" aria-label="Subject"
										           aria-describedby="basic-addon1">
										</div>
	                        		</div>
	                        	</div>
	                        	<div class="row no-gutters flex-wrap flex-sm-nowrap align-items-center py-2 px-3 py-sm-4 px-sm-6 unread">
	                        		<div class="col-12 col-sm-auto d-flex flex-sm-column justify-content-center align-items-center" style="width: 100%;">
										<div class="input-group">
	                                        <textarea id="txt_body" name="txt_body" class="form-control" placeholder="Type and hit enter to send message" style="min-height: 300px;"></textarea>
	                                    </div>
	                        		</div>
	                        	</div>
	                        	<div class="row no-gutters flex-wrap flex-sm-nowrap align-items-center py-2 px-3 py-sm-4 px-sm-6 unread">
	                        		<div class="col-12 col-sm-auto d-flex flex-sm-column justify-content-center align-items-center" style="width: 100%;">
										<div class="input-group">
	                                        <button type="button" class="btn btn-secondary" onclick="send_msg()">Send</button>
	                                    </div>
	                        		</div>
	                        	</div>
	                        </form>
                        </div>
                    </div>

                    <!-- / CONTENT -->
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
	function send_msg()
	{
		if($("#txt_recipients").val() == "")
		{
			alert('Please specify at least one recipient.');
			return;
		}

		$('#frm_new_msg').attr('action', '<? echo site_url()."index.php/nylas_mail/mail_send"; ?>');
		$("#frm_new_msg").submit();
	}
</script>