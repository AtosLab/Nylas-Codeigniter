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

                                    <label class="custom-control custom-checkbox">
                                        <input id="select_all" type="checkbox" class="custom-control-input" />
                                        <span class="custom-control-indicator"></span>
                                    </label>

                                </div>

                                <div class="action-buttons col">
                                    <div class="row no-gutters align-items-center flex-nowrap d-none d-xl-flex">

                                        <div class="divider-vertical"></div>
                                        <? $refresh_url = site_url().'index.php/nylas_mail/index/'.$sub_menu['label_active']; ?>
                                        <button type="button" class="btn btn-icon" aria-label="refresh" onclick="location.href='<? echo $refresh_url ?>'" >
                                            <i class="icon icon-refresh"></i>
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

                                <? if($page_nav['total'] > 0) { ?>
                                    <span class="page-info px-2 d-none d-sm-block"><? echo ($page_nav['start']+1).' - '.$page_nav['end']. ' of '.$page_nav['total']; ?></span>

                                    <button type="button" class="btn btn-icon">
                                        <i class="icon icon-chevron-left"></i>
                                    </button>

                                    <button type="button" class="btn btn-icon">
                                        <i class="icon icon-chevron-right"></i>
                                    </button>

                                    <button type="button" class="btn btn-icon">
                                        <i class="icon icon-settings"></i>
                                    </button>
                                <? } ?>

                            </div>
                        </div>
                    </div>

                    <!-- / CONTENT TOOLBAR -->
                    <div class="page-content custom-scrollbar">
                        <div class="thread-list w-100">
                        <?
                        foreach ($messages as $msg) {
                            $id = $msg->id;
                            $msg = $msg->json();
                            $view_url = site_url().'index.php/nylas_mail/mail_view/'.$sub_menu['label_active'].'/'.$id;
                            ?>
                            <div class="thread ripple row no-gutters flex-wrap flex-sm-nowrap align-items-center py-2 px-3 py-sm-4 px-sm-6 <? echo $msg['unread'] ? 'unread' : ''; ?>" onclick="location.href='<? echo $view_url ?>'" >
                                <label class="col-auto custom-control custom-checkbox">
                                    <input id="sub_item_check" type="checkbox" class="custom-control-input" />
                                    <span id="sub_item_check_span" class="custom-control-indicator"></span>
                                </label>

                                <div class="info col px-4">
                                    
                                    <div class="subject">
                                        <? echo $msg['subject']; ?>
                                    </div>
                                    <div class="message">
                                        <? echo $msg['snippet']; ?>
                                    </div>
                                </div>
                        
                                <div class="col-12 col-sm-auto d-flex flex-sm-column justify-content-center align-items-center">
                                    <div class="time mb-2"><? echo gmdate("M d", $msg['date']); ?></div>
                                    <div class="actions row no-gutters">
                                        <button id="<? echo 'btn_starred'.$id; ?>" type="button" class="btn btn-icon" onclick="set_starred(event, <? echo $msg['starred'] ? "'false'".",'".$id."'" : "'true'".",'".$id."'"; ?>)" >
                                            <i id="<? echo 'icon_starred'.$id; ?>" class="<? echo $msg['starred'] ? 'icon-star' : 'icon-star-outline' ; ?>"></i>
                                        </button>

                                        <button type="button" class="btn btn-icon">
                                            <i class="icon-label-outline"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        <?
                        }
                        ?>
                        </div>
                    </div>

                    <!-- / CONTENT -->
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
          
    });
        
    //select all checkboxes
    $("#select_all").change(function(){  //"select all" change 
        $(".custom-control-input").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    $('#sub_item_check').click(function(event) {
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        if ($('#sub_item_check:checked').length == $('#sub_item_check').length ){
            $("#select_all").prop('checked', true);
        }

        if (event.stopPropagation) {    // standard
            event.stopPropagation();
        } else {    // IE6-8
            event.cancelBubble = true;
        }
    });
    $('#sub_item_check_span').click(function(event) {
        if (event.stopPropagation) {    // standard
            event.stopPropagation();
        } else {    // IE6-8
            event.cancelBubble = true;
        }
    });   


    function set_starred(e, bflag, msg_id)
    {
        alert('adf');
        if($(e.target).is("i")) {
            e.stopPropagation();
        }
        var url = "<? echo site_url().'index.php/nylas_mail/set_starred'; ?>";
        //url += msg_id + "/" + "true";
        $.ajax({
            type: "POST",
            url: url, 
            data: {msg_id: msg_id, set_value: bflag},
            success : 
                function(msg) {
                    msg = JSON.parse(msg);
                    if(msg == 'success')
                    {
                        if(bflag == "true"){
                            bflag = "false";
                            $("#icon_starred"+msg_id).attr('class', 'icon-star');
                            $("#btn_starred"+msg_id).attr('onclick', 'set_starred(event, "'+bflag+'", "'+msg_id+'")');
                        }
                        else{
                            bflag = "true";
                            $("#icon_starred"+msg_id).attr('class', 'icon-star-outline');
                            $("#btn_starred"+msg_id).attr('onclick', 'set_starred(event, "'+bflag+'", "'+msg_id+'")');
                        }
                    }
                },
            error : 
                function(data) {
                    
                }
        });
    }
   
</script>