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
                                        <input id="select_all" type="checkbox" class="custom-control-input select-all" />
                                        <span class="custom-control-indicator select-all"></span>
                                    </label>

                                </div>

                                <div class="action-buttons col">
                                    <div class="row no-gutters align-items-center flex-nowrap d-none d-xl-flex">

                                        <div class="divider-vertical"></div>
                                        <? $refresh_url = site_url().'index.php/nylas_mail/index/'.$sub_menu['label_active']; ?>
                                        <button type="button" class="btn btn-icon" aria-label="refresh" onclick="location.href='<? echo $refresh_url ?>'" >
                                            <i class="icon icon-refresh"></i>
                                        </button>

                                        <div id="div_divider1" class="divider-vertical"></div>

                                        <button id="btn_spam" type="button" class="btn btn-icon" aria-label="spam" onclick="move_to_spam()">
                                            <i class="icon icon-alert-octagon"></i>
                                        </button>

                                        <button id="btn_delete" type="button" class="btn btn-icon" aria-label="delete" onclick="move_to_trash()">
                                            <i class="icon icon-delete"></i>
                                        </button>

                                        <div id="div_divider2" class="divider-vertical"></div>

                                        <button id="btn_move_to" type="button" class="btn btn-icon" aria-label="move to">
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

                                <? if($page_nav['total'] > 0) {
                                    $page_back_start = ($page_nav['start'] - 10) > 0 ? ($page_nav['start'] - 10) : 0;
                                    $page_next_start = ($page_nav['start'] + 10) > $page_nav['total'] ? $page_nav['start'] : ($page_nav['start'] + 10);
                                    $page_back = site_url().'index.php/nylas_mail/index/'.$sub_menu['label_active'].'/'.$page_back_start;
                                    $page_next = site_url().'index.php/nylas_mail/index/'.$sub_menu['label_active'].'/'.$page_next_start;
                                ?>
                                    <span class="page-info px-2 d-none d-sm-block"><? echo ($page_nav['start']+1).' - '.$page_nav['end']. ' of '.$page_nav['total']; ?></span>

                                    <button type="button" class="btn btn-icon" <? echo $page_back_start == 0 ? 'disabled="disabled"' : ''; ?> onclick="location.href='<? echo $page_back ?>'">
                                        <i class="icon icon-chevron-left"></i>
                                    </button>

                                    <button type="button" class="btn btn-icon" <? echo ($page_next_start + 10) >= $page_nav['total'] ? 'disabled="disabled"' : ''; ?> onclick="location.href='<? echo $page_next ?>'">
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
                        <form id="frm_check" method="post">
                        <?
                        foreach ($messages as $msg) {
                            $id = $msg->id;
                            $msg = $msg->json();
                            $view_url = site_url().'index.php/nylas_mail/mail_view/'.$sub_menu['label_active'].'/'.$id;
                            ?>
                            <input type="hidden" name="sub_menu" value="<?=$sub_menu['label_active']; ?>">
                            <div class="thread ripple row no-gutters flex-wrap flex-sm-nowrap align-items-center py-2 px-3 py-sm-4 px-sm-6 <? echo $msg['unread'] ? 'unread' : ''; ?>" onclick="location.href='<? echo $view_url ?>'" >
                                <label class="col-auto custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input sub-checked-item" name="check_list[]" value="<?= $id ?>" />
                                    <span class="custom-control-indicator"></span>
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


    $(document).ready(function() {
        showSelectedActionBtn(false);
    });
    
    function showSelectedActionBtn(bflag, foldername = 'inbox')
    {
        if(bflag)
        {
            $("#div_divider1").css("display", "");
            $("#div_divider2").css("display", "");
            if(foldername == 'inbox'){
                $("#btn_spam").css("display", "");
                $("#btn_delete").css("display", "");
            }
            else if(foldername == 'spam'){
                $("#btn_delete").css("display", "");
            }
            else if(foldername == 'trash'){
                $("#btn_spam").css("display", "");
            }
            $("#btn_move_to").css("display", "");
        }
        else
        {
            $("#div_divider1").css("display", "none");
            $("#div_divider2").css("display", "none");
            $("#btn_spam").css("display", "none");
            $("#btn_delete").css("display", "none");
            $("#btn_move_to").css("display", "none");
        }
    }
    //select all checkboxes
    $("#select_all").change(function(){  //"select all" change 
        $(".sub-checked-item").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
        showSelectedActionBtn($(this).prop("checked"), '<?= $sub_menu['label_active']; ?>');
    });

    $('.sub-checked-item').click(function(event) {
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        if ($('.sub-checked-item:checked').length == $('.sub-checked-item').length ){
            $("#select_all").prop('checked', true);
        }
        showSelectedActionBtn($('.sub-checked-item:checked').length, '<?= $sub_menu['label_active']; ?>');

        if (event.stopPropagation) {    // standard
            event.stopPropagation();
        } else {    // IE6-8
            event.cancelBubble = true;
        }
    });
    $('.custom-control-indicator').click(function(event) {
        if (event.stopPropagation) {    // standard
            event.stopPropagation();
        } else {    // IE6-8
            event.cancelBubble = true;
        }
    });  

    function move_to_trash()
    {
        if($('.sub-checked-item:checked').length == 0)
            return;
        $('#frm_check').attr('action', '<? echo site_url()."index.php/nylas_mail/mail_to_trash_bulk"; ?>');
        $("#frm_check").submit();
    } 

    function move_to_spam()
    {
        if($('.sub-checked-item:checked').length == 0)
            return;
        $('#frm_check').attr('action', '<? echo site_url()."index.php/nylas_mail/mail_to_spam_bulk"; ?>');
        $("#frm_check").submit();
    } 


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