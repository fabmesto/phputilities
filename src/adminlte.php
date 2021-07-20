<?php

namespace fab;

class adminlte
{

    public static function page_header($title, $description, $icon = '', $right = true)
    {
        $html = '';
        $html .= '
                <h2 class="page-header clearfix">
                ' . $icon . ' ' . $title . '
                <small ' . ($right ? 'class="pull-right"' : '') . '>' . $description . '</small>
                </h2>
            ';
        return $html;
    }

    public static function box($header_html, $body_html, $footer_html = '', $classes = array())
    {
        $html = '';
        $main_class = '';
        if (isset($classes['main'])) {
            $main_class = $classes['main'];
        }

        $header_class = '';
        if (isset($classes['header'])) {
            $header_class = $classes['header'];
        }

        $body_class = '';
        if (isset($classes['body'])) {
            $body_class = $classes['body'];
        }

        $footer_class = '';
        if (isset($classes['footer'])) {
            $footer_class = $classes['footer'];
        }
        $html .= '
            <div class="box ' . $main_class . '">
                <div class="box-header ' . $header_class . '">
                ' . $header_html . '
                </div>
                <div class="box-body ' . $body_class . '">
                ' . $body_html . '
                </div>
                ';
        if ($footer_html != '') {
            $html .= '
                <div class="box-footer ' . $footer_class . '">
                ' . $footer_html . '
                </div>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function card($header_html, $body_html, $footer_html = '', $classes = array())
    {
        $html = '';
        $main_class = '';
        if (isset($classes['main'])) {
            $main_class = $classes['main'];
        }

        $header_class = '';
        if (isset($classes['header'])) {
            $header_class = $classes['header'];
        }

        $body_class = '';
        if (isset($classes['body'])) {
            $body_class = $classes['body'];
        }

        $footer_class = '';
        if (isset($classes['footer'])) {
            $footer_class = $classes['footer'];
        }
        $html .= '
            <div class="card ' . $main_class . '">
                <div class="card-header ' . $header_class . '">
                ' . $header_html . '
                </div>
                <div class="card-body ' . $body_class . '">
                ' . $body_html . '
                </div>
                ';
        if ($footer_html != '') {
            $html .= '
                <div class="card-footer ' . $footer_class . '">
                ' . $footer_html . '
                </div>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function box_profile($url_avatar_img, $username, $description)
    {
        $html = '';
        $html .= '
            <div class="box-profile">
                <div class="text-center"><img class="profile-user-img img-responsive img-circle" src="' . $url_avatar_img . '" alt="' . $username . '"></div>
                <h3 class="profile-username text-center">' . $username . '</h3>
                <p class="text-muted text-center">' . $description . '</p>
            </div>';
        return $html;
    }
    public static function user_block($url_avatar_img, $username, $description)
    {
        $html = '';
        $html .= '
            <div class="user-block">
                <img class="img-circle img-bordered-sm" src="' . $url_avatar_img . '" alt="' . $username . '">
                <span class="username">
                    ' . $username . '
                </span>
                <span class="description">' . $description . '</span>
            </div>
            ';
        return $html;
    }

    public static function direct_chat_msg($url_avatar_img, $username, $date, $text, $right = false)
    {
        $html = '';
        $html .= '
                <div class="direct-chat-msg ' . ($right ? 'right' : '') . '">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">' . $username . '</span>
                    <span class="direct-chat-timestamp pull-right">' . $date . '</span>
                  </div>
                  <!-- /.direct-chat-info -->
                  <img class="direct-chat-img" src="' . $url_avatar_img . '" alt="' . $username . '"><!-- /.direct-chat-img -->
                  <div class="direct-chat-text">
                    ' . $text . '
                  </div>
                  <!-- /.direct-chat-text -->
                </div>
                ';
        return $html;
    }

    public static function info_box($icon, $text, $number, $bg_box = '', $bg_icon = '', $progress_percent = false, $progress_descrption = '')
    {
        $html = '';
        $html .= '
            <div class="info-box ' . $bg_box . '">
            ';
        if ($icon != '') {
            $html .= '<span class="info-box-icon ' . $bg_icon . '">' . $icon . '</span>';
        }
        $html .= '
                <div class="info-box-content">
                    <span class="info-box-text">' . $text . '</span>
                    <span class="info-box-number">' . $number . '</span>
            ';
        if ($progress_percent) {
            $html .= '
                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    ';
        }
        if ($progress_descrption != '') {
            $html .= '
                    <span class="progress-description">
                        ' . $progress_descrption . '
                    </span>';
        }
        $html .= '
                </div>
            </div>
            ';
        return $html;
    }

    public static function box_tools($html_buttons = '', $collapse = false, $remove = false)
    {
        $html = '';
        $html .= '<div class="box-tools">';
        $html .= $html_buttons;
        if ($collapse) {
            $html .= '
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                    </button>';
        }
        if ($remove) {
            $html .= '
                    <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                    </button>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function small_box($icon, $title, $description, $bg_box = '', $url = '#', $url_text = '')
    {
        $html = '';
        $html .= '
            <div class="small-box ' . $bg_box . '">
                <div class="inner">
                    <h3>' . $title . '</h3>

                    <p>' . $description . '</p>
                </div>
                <div class="icon">
                    ' . $icon . '
                </div>
                ';
        if ($url_text != '') {
            $html .= '
                <a href="' . $url . '" class="small-box-footer">
                    ' . $url_text . ' <i class="fa fa-arrow-circle-right"></i>
                </a>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function box_comment($url_avatar_img, $username, $date, $comment_text)
    {
        $html = '';
        $html .= '
            <div class="box-comment">
                <!-- User image -->
                <img class="img-circle img-sm" src="' . $url_avatar_img . '" alt="' . $username . '">

                <div class="comment-text">
                      <span class="username">
                      ' . $username . '
                        <span class="text-muted pull-right">' . $date . '</span>
                      </span><!-- /.username -->
                 ' . $comment_text . '
                </div>
                <!-- /.comment-text -->
                ';
        $html .= '</div>';
        return $html;
    }

    public static function box_widget_user($url_avatar_img, $username, $description, $html_footer, $bg_color = 'bg-aqua-active')
    {
        $html = '';
        $html .= '
            <div class="box box-widget widget-user">
                <div class="widget-user-header ' . $bg_color . '">
                    <h3 class="widget-user-username">' . $username . '</h3>
                    <h5 class="widget-user-desc">' . $description . '</h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle" src="' . $url_avatar_img . '" alt="' . $username . '">
                </div>
                <div class="box-footer">
                ' . $html_footer . '
                </div>
            </div>
            ';
        return $html;
    }

    public static function box_widget_user2($url_avatar_img, $username, $description, $html_footer = '',  $bg_color = 'bg-aqua-active')
    {
        $html = '';
        $html .= '
                <div class="box box-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header ' . $bg_color . '">
                        <div class="widget-user-image">
                            <img class="img-circle" src="' . $url_avatar_img . '" alt="' . $username . '">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">' . $username . '</h3>
                        <h5 class="widget-user-desc">' . $description . '</h5>
                    </div>
                    <div class="box-footer">
                    ' . $html_footer . '
                    </div>
                </div>
            ';
        return $html;
    }

    public static function card_widget_user($url_avatar_img, $username, $description, $html_footer, $url_cover = '')
    {
        $html = '';
        $html .= '
            <div class="card card-widget widget-user">
                <div class="widget-user-header text-white" style="background: url(' . $url_cover . ') center center;">
                    <h3 class="widget-user-username text-right">' . $username . '</h3>
                    <h5 class="widget-user-desc text-right">' . $description . '</h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle" src="' . $url_avatar_img . '" alt="' . $username . '">
                </div>
                <div class="card-footer">
                ' . $html_footer . '
                </div>
            </div>
            ';
        return $html;
    }

    public static function card_widget($url_cover, $title, $subtitle, $html_footer)
    {
        $html = '';
        $html .= '
            <div class="card card-widget widget-user">
                <div class="widget-user-header" style="background: url(' . $url_cover . ') center center;">
                    <h3 class="widget-user-username">' . $title . '</h3>
                    <h5 class="widget-user-desc">' . $subtitle . '</h5>
                </div>
                <div class="card-footer">
                ' . $html_footer . '
                </div>
            </div>
            ';
        return $html;
    }

    public static function card_tools()
    {
        $html = '';
        $html .= '
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                </button>
            </div>
            ';
        return $html;
    }

    public static function alert($type, $message)
    {
        $html = '
            <div class="alert alert-' . $type . ' alert-dismissible" role="alert">
            ' . $message . '
            </div>';
        return $html;
    }
}
