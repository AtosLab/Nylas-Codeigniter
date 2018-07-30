<div class="content-wrapper">
    <div class="content custom-scrollbar">

        <div id="mail" class="page-layout carded left-sidebar">

            <div class="top-bg bg-primary"></div>

            <aside class="page-sidebar" data-fuse-bar="mail-sidebar" data-fuse-bar-media-step="md">
                <!-- HEADER -->
                <div class="header d-flex flex-column justify-content-between p-6 light-fg">

                    <div class="logo d-flex align-items-center pt-7">
                        <i class="icon-email mr-4"></i>
                        <span class="logo-text h4">Mailbox</span>
                    </div>

                    <div class="account">
                        <div class="title"><? echo $account->email_address; ?></div>
                    </div>

                </div>
                <!-- / HEADER -->

                <div class="content custom-scrollbar">

                    <div class="p-6">
                        <? $view_url = site_url().'index.php/nylas_mail/mail_new'; ?>
                        <button type="button" class="btn btn-secondary btn-block" onclick="location.href='<? echo $view_url ?>'">COMPOSE</button>
                    </div>

                    <ul class="nav flex-column">
                    <?
	                    /*foreach ($labels as $label) {
	                    	echo '<li class="nav-item '.($label['is_active'] ? 'nav_active' : '').' ">';
	                        echo '	<a class="nav-link ripple" href="'. $label['urls'] .'">';
	                        echo '		<i class="' . $label['icons'] . '"></i>';
	                        echo '		<span>' .$label['display_name']. '</span>';
	                        echo '	</a>';
	                        echo '</li>';
	                    }*/
                    ?>
                        <li class="nav-item <? echo ($label_active == 'inbox' ? 'nav-active' : ''); ?> ">
                            <a class="nav-link ripple" href="<? echo site_url().'index.php/nylas_mail/index'; ?>">
                                <i class="icon s-4 icon-inbox"></i>
                                <span>Inbox</span>
                            </a>
                        </li>

                        <li class="nav-item <? echo ($label_active == 'sent' ? 'nav-active' : ''); ?> ">
                            <a class="nav-link ripple" href="<? echo site_url().'index.php/nylas_mail/index/sent'; ?>">
                                <i class="icon s-4 icon-send"></i>
                                <span>Sent</span>
                            </a>
                        </li>

                        <!--<li class="nav-item <? echo ($label_active == 'drafts' ? 'nav-active' : ''); ?> ">
                            <a class="nav-link ripple" href="<? echo site_url().'index.php/nylas_mail/index/drafts'; ?>">
                                <i class="icon s-4 icon-email-open"></i>
                                <span>Drafts</span>
                            </a>
                        </li>-->

                        <li class="nav-item <? echo ($label_active == 'trash' ? 'nav-active' : ''); ?> ">
                            <a class="nav-link ripple" href="<? echo site_url().'index.php/nylas_mail/index/trash'; ?>">
                                <i class="icon s-4 icon-delete"></i>
                                <span>Trash</span>
                            </a>
                        </li>

                        <li class="nav-item <? echo ($label_active == 'spam' ? 'nav-active' : ''); ?> ">
                            <a class="nav-link ripple" href="<? echo site_url().'index.php/nylas_mail/index/spam'; ?>">
                                <i class="icon s-4 icon-alert-octagon"></i>
                                <span>Spam</span>
                            </a>
                        </li>

                        <li class="nav-item <? echo ($label_active == 'starred' ? 'nav-active' : ''); ?> ">
                            <a class="nav-link ripple" href="<? echo site_url().'index.php/nylas_mail/index/starred'; ?>">
                                <i class="icon s-4 icon-star"></i>
                                <span>Starred</span>
                            </a>
                        </li>

                        <li class="nav-item <? echo ($label_active == 'important' ? 'nav-active' : ''); ?> ">
                            <a class="nav-link ripple" href="<? echo site_url().'index.php/nylas_mail/index/important'; ?>">
                                <i class="icon s-4 icon-label"></i>
                                <span>Important</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>