<section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="{{auth()->user()->avatar}}" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{auth()->user()->name}}</div>
                    <div class="email">{{auth()->user()->email}}</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            {{-- <li><a href="javascript:void(0);"><i class="material-icons">settings</i>Settings</a></li> --}}
                            {{-- <li role="seperator" class="divider"></li> --}}
                            <li><a href="{{route('staff.index', $restaurant->slug)}}"><i class="material-icons">face</i>Nhân viên</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="{{route('logout')}}"><i class="material-icons">exit_to_app</i>Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li {{strpos(url()->current(), 'menu') ? 'class=active':''}}>
                        <a href="{{route('category.index', $restaurant->slug)}}">
                            <i class="material-icons">restaurant_menu</i>
                            <span>Menu</span>
                        </a>
                    </li>
                    <li {{ strpos(url()->current(), 'reservation') ? 'class=active':''}}>
                        <a href="{{route('reservation.index', $restaurant->slug)}}">
                            <i class="material-icons">date_range</i>
                            <span>Đơn đặt bàn</span>
                        </a>
                    </li>
                    <li {{ strpos(url()->current(), 'order') ? 'class=active':''}}>
                        <a href="{{route('order.index', $restaurant->slug)}}">
                            <i class="material-icons">shopping_cart</i>
                            <span>Đơn hàng ship đồ ăn</span>
                        </a>
                    </li>
{{--                     <li {{ strpos(url()->current(), 'discount') ? 'class=active':''}}>
                        <a href="{{route('discount.index', $restaurant->slug)}}">
                            <i class="material-icons">loyalty</i>
                            <span>Discounts</span>
                        </a>
                    </li> --}}
                    <li {{ strpos(url()->current(), '/bot/') ? 'class=active':''}}>
                        <a href="{{route('bot.index', $restaurant->slug)}}">
                            <i class="material-icons">chat</i>
                            <span>Cài đặt chatbot</span>
                        </a>
                    </li>
                    <li {{ strpos(url()->current(), 'customer') ? 'class=active':''}}>
                        <a href="{{route('customer.index', $restaurant->slug)}}">
                            <i class="material-icons">people</i>
                            <span>Quan hệ khách hàng</span>
                        </a>
                    </li>
                    <li {{ strpos(url()->current(), 'contact') ? 'class=active':''}}>
                        <a href="{{route('contact.index', $restaurant->slug)}}">
                            <i class="material-icons">contact_phone</i>
                            <span>Thông tin liên hệ</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
{{--             <div class="legal">
                <div class="copyright">
                    &copy; 2016 <a href="javascript:void(0);">AdminBSB - Material Design</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.0.4
                </div>
            </div> --}}
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="red">
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green" class="active">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <div class="demo-settings">
                        <p>GENERAL SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Report Panel Usage</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Email Redirect</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>SYSTEM SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Notifications</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Auto Updates</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>ACCOUNT SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Offline</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Location Permission</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>