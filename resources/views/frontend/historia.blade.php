@extends('layouts.front_master')
@section('content')

<div class="innerbanner">
    <img src="../wp-content/themes/prayer-pro/images/inner-banner.jpg" alt="">
</div>


<div class="container content-area">
    <div class="middle-align">
        <div class="site-main" id="sitemain">
            <header>
                <h1 class="entry-title">Historia</h1>
            </header>
            @if($data_historia->isNotEmpty())

            @foreach ($data_historia as $item)
            <div class="blog-post-repeat">
                <article id="post-1777"
                    class="post-1777 post type-post status-publish format-standard has-post-thumbnail hentry category-uncategorized">
                    <header class="entry-header">
                        <h3 class="post-title"><a
                                href="#"
                                rel="bookmark">{{$item->kichwa}}</a></h3>

                                <div class="postmeta">
                                    <div class="post-date">{{Carbon::parse($item->tarehe)->format('M d, Y')}}</div><!-- post-date -->
                                    <div class="clear"></div>
                                </div>
                        <div class="post-thumb"><a
                                href="#"><img
                                    width="800" height="535" src="{{url('uploads/images/'.$item->picha)}}"
                                    class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt=""
                                    loading="lazy"
                                    srcset="{{url('uploads/images/'.$item->picha)}} 800w, {{url('uploads/images/'.$item->picha)}} 300w, {{url('uploads/images/'.$item->picha)}} 768w"
                                    sizes="(max-width: 800px) 100vw, 800px" /></a></div>


                    </header><!-- .entry-header -->

                    <div class="entry-summary">
                        <p>   {{$item->maelezo}}</p>


                        <!-- <p class="read-more"><a
                                href="../2019/08/29/aliquam-placerat-tellus-ac-laoreet-euismod/index.html">
                                →</a></p> -->
                        <div class="clear"></div>
                    </div><!-- .entry-summary -->

                </article><!-- #post-## -->
            </div>
            @endforeach
            @else
            <div class="blog-post-repeat">
                <article id="post-1432"
                    class="post-1432 post type-post status-publish format-standard has-post-thumbnail hentry category-uncategorized">
                    <header class="entry-header">
                        <h3 class="post-title"><a
                                href="#"
                                rel="bookmark">Ukurasa wa historia</a></h3>
                        <div class="postmeta">
                            <div class="post-date">{{Carbon::now()->format('M d, Y')}}</div><!-- post-date -->

                            <div class="clear"></div>
                        </div><!-- postmeta -->

                        <div class="post-thumb"><a
                                href="#"><img
                                    width="800" height="535" src="{{url('uploads/church.jpg')}}"
                                    class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt=""
                                    loading="lazy"
                                    srcset="{{url('uploads/church.jpg')}} 800w, {{url('uploads/church.jpg')}} 300w, {{url('uploads/church.jpg')}} 768w"
                                    sizes="(max-width: 800px) 100vw, 800px" /></a></div>


                    </header><!-- .entry-header -->

                    <div class="entry-summary">
                        <p>Karibu katika ukurasa wa historia za watakatifu mbalimbali, ukurasa huu utahusisha historia za watakatifu mbalimbali walioshiriki kikamilifu katika historia ya kanisa..</p>
                        <div class="clear"></div>
                    </div><!-- .entry-summary -->

                </article><!-- #post-## -->
            </div>
            @endif
        </div>
        <div id="sidebar">

           
            <aside id="recent-posts-2" class="widget widget_recent_entries">
                <h3 class="widget-title">Historia za karibuni</h3>
                <ul>
                    @if($data_historia->isNotEmpty())
                    @foreach ($data_historia as $row)
                    <li>
                        <a href="#">{{$item->kichwa}}</a>
                    </li>
                    @endforeach
                   @else
                   <li>
                    <a href="#">Ukuraa wa historia</a>
                </li>
                   @endif
             
                </ul>

            </aside>
           
        </div><!-- sidebar -->
        <div class="clear"></div>
    </div>
</div>
@endsection