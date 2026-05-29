@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="user-profile-card profile-message">
            <div class="user-profile-card-header">
                <h4 class="user-profile-card-title">Messages</h4>
                <div class="user-profile-card-header-right">
                    <div class="header-account">
                        <div class="dropdown">
                            <div data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="assets/img/account/01.jpg" alt="">
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="far fa-ban"></i> Block Chat</a></li>
                                <li><a class="dropdown-item" href="#"><i class="far fa-message-slash"></i> Mute Chat</a></li>
                                <li><a class="dropdown-item" href="#"><i class="far fa-trash-can"></i> Delete Chat</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="profile-message-wrapper">
                        <div class="profile-message-inbox">
                            <ul class="profile-message-list">
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/01.jpg" alt="">
                                            <span class="message-status online"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Angela Howe</h5>
                                                <span>just now</span>
                                            </div>
                                            <p>Hello, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/02.jpg" alt="">
                                            <span class="message-status offline"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Roger Knight</h5>
                                                <span>15 min ago</span>
                                            </div>
                                            <p>Hi, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li class="message-active">
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/03.jpg" alt="">
                                            <span class="message-status busy"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Rikki Hamby</h5>
                                                <span>5 hours ago</span>
                                            </div>
                                            <p>Hello, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/04.jpg" alt="">
                                            <span class="message-status online"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Arlene Lawrence</h5>
                                                <span>Yesterday</span>
                                            </div>
                                            <p>Hi, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/05.jpg" alt="">
                                            <span class="message-status busy"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Donald Ledoux</h5>
                                                <span>2 week ago</span>
                                            </div>
                                            <p>Hello, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/01.jpg" alt="">
                                            <span class="message-status online"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Hope Stanley</h5>
                                                <span>1 months ago</span>
                                            </div>
                                            <p>Hi, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/02.jpg" alt="">
                                            <span class="message-status offline"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Rob Madden</h5>
                                                <span>Sep 11, 2025</span>
                                            </div>
                                            <p>Hello, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/03.jpg" alt="">
                                            <span class="message-status online"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Dawne Martin</h5>
                                                <span>Sep 15, 2025</span>
                                            </div>
                                            <p>Hi, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/04.jpg" alt="">
                                            <span class="message-status busy"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Nicholas Diedrich</h5>
                                                <span>Sep 20, 2025</span>
                                            </div>
                                            <p>Hello, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/05.jpg" alt="">
                                            <span class="message-status busy"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Denise Garrett</h5>
                                                <span>Sep 25, 2025</span>
                                            </div>
                                            <p>Hi, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/01.jpg" alt="">
                                            <span class="message-status offline"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Justin Garza</h5>
                                                <span>Sep 26, 2025</span>
                                            </div>
                                            <p>Hello, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="message-avatar">
                                            <img src="assets/img/account/02.jpg" alt="">
                                            <span class="message-status online"></span>
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-content">
                                                <h5>Jenna Lemon</h5>
                                                <span>Sep 28, 2025</span>
                                            </div>
                                            <p>Hi, It is a long establish fact that a reader will distracted</p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="message-content">
                            <div class="message-content-info">
                                <div class="message-item">
                                    <div class="message-avatar">
                                        <img src="assets/img/account/01.jpg" alt="">
                                    </div>
                                    <div class="message-description">
                                        <p>
                                            Hello, It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                                        </p>
                                    </div>
                                </div>
                                <div class="message-item me">
                                    <div class="message-avatar">
                                        <img src="assets/img/account/02.jpg" alt="">
                                    </div>
                                    <div class="message-description">
                                        <p>
                                            There are many variations of passages available but the majority have suffered alteration in some form by injected humour.
                                        </p>
                                    </div>
                                </div>
                                <div class="message-item">
                                    <div class="message-avatar">
                                        <img src="assets/img/account/01.jpg" alt="">
                                    </div>
                                    <div class="message-description">
                                        <p>
                                            We denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment.
                                        </p>
                                    </div>
                                </div>
                                <div class="message-item me">
                                    <div class="message-avatar">
                                        <img src="assets/img/account/02.jpg" alt="">
                                    </div>
                                    <div class="message-description">
                                        <p>
                                            So blinded by desire that they cannot foresee the pain and trouble that are bound to ensue.
                                        </p>
                                    </div>
                                </div>
                                <div class="message-item">
                                    <div class="message-avatar">
                                        <img src="assets/img/account/01.jpg" alt="">
                                    </div>
                                    <div class="message-description">
                                        <p>
                                            In a free hour when our power of choice is untra and when nothing prevents our being able.
                                        </p>
                                    </div>
                                </div>
                                <div class="message-item me">
                                    <div class="message-avatar">
                                        <img src="assets/img/account/02.jpg" alt="">
                                    </div>
                                    <div class="message-description">
                                        <p>
                                            We like best every pleasure is to be welcomed and every pain avoided in certain circums and owing to the claims of duty.
                                        </p>
                                    </div>
                                </div>
                                <div class="message-item">
                                    <div class="message-avatar">
                                        <img src="assets/img/account/01.jpg" alt="">
                                    </div>
                                    <div class="message-description">
                                        <p>
                                            The obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="message-reply">
                                <textarea cols="40" rows="3" class="form-control"
                                    placeholder="Your Message"></textarea>
                                <button type="submit" class="theme-btn"><span class="far fa-paper-plane"></span> Send Message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection