<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Crèche Platform')</title>
        @include('layouts.admin.head')
      
    @yield('styles')
</head>
<body class="w3-light-grey">

    <!-- Navigation -->
    <div class="w3-bar w3-blue">
        <a href="/" class="w3-bar-item w3-button">Home</a>
        <a href="/learners" class="w3-bar-item w3-button">Learners</a>
        <a href="/settings" class="w3-bar-item w3-button">Settings</a>
    </div>

    <div class="w3-content w3-padding-32" style="max-width:1100px">
        @yield('content')
    </div>

    <footer class="w3-container w3-blue w3-center w3-padding-16">
        Multi-Tenant Crèche Platform
    </footer>

    @yield('scripts')
</body>
</html>
