<header class="w-full px-6 pb-12 antialiased bg-white">
    <nav class="relative z-50 h-16 select-none mx-auto max-w-7xl">
        <div class="container relative flex flex-wrap items-center justify-between h-16 mx-auto overflow-hidden font-medium border-b border-gray-200 md:overflow-visible lg:justify-center sm:px-4 md:px-2 lg:px-0">
            <div class="flex items-center justify-start w-2/4 md:w-1/4 h-full pr-4">
                <a href="<?php echo ($base_url) ?>" class="flex items-center py-4 space-x-2 font-extrabold text-gray-900 md:py-0">
                    <span class="flex items-center justify-center w-8 h-8 text-white bg-gray-900 rounded-full">
                        <img src="<?php echo('assets/imgs/logo.png') ?>" alt="PantherPHP Logo" class="w-auto h-7 -translate-y-px">
                    </span>
                    <span><?php echo($project_name) ?></span>
                </a>
            </div>
            <div class="top-0 left-0 items-start hidden w-full h-full p-4 text-sm bg-gray-900 bg-opacity-50 md:items-center md:w-3/4 md:absolute lg:text-base md:bg-transparent md:p-0 md:relative md:flex closed" id="menu">
                <div class="flex-col w-full h-auto overflow-hidden bg-white rounded-lg md:bg-transparent md:overflow-visible md:rounded-none md:relative md:flex md:flex-row">
                    <a href="#_" class="inline-flex items-center block w-auto h-16 px-6 space-x-2 font-black leading-none text-gray-900 md:hidden">
                        <span class="flex items-center justify-center w-8 h-8 text-white bg-gray-900 rounded-full">
                        <img src="<?php echo('assets/imgs/logo.png') ?>" alt="PantherPHP Logo" class="w-auto h-7 -translate-y-px">
                        </span>
                        <span><?php echo($project_name) ?></span>
                    </a>
                    <div class="flex flex-col items-start justify-center w-full space-x-6 text-center lg:space-x-8 md:w-2/3 md:mt-0 md:flex-row md:items-center">
                        <a href="<?php echo ($base_url) . 'about' ?>" class="inline-block w-full py-2 mx-0 ml-6 font-medium text-left text-black md:ml-0 md:w-auto md:px-0 md:mx-2 lg:mx-3 md:text-center">About</a>
                        <a href="<?php echo ($base_url) . 'pricing' ?>" class="inline-block w-full py-2 mx-0 font-medium text-left text-gray-700 md:w-auto md:px-0 md:mx-2 hover:text-black lg:mx-3 md:text-center">Pricing</a>
                        <a href="#_" class="inline-block w-full py-2 mx-0 font-medium text-left text-gray-700 md:w-auto md:px-0 md:mx-2 hover:text-black lg:mx-3 md:text-center">Blog</a>
                        <a href="<?php echo ($base_url) . 'contact' ?>" class="inline-block w-full py-2 mx-0 font-medium text-left text-gray-700 md:w-auto md:px-0 md:mx-2 hover:text-black lg:mx-3 md:text-center">Contact</a>
                    </div>
                    <div class="flex flex-col items-start justify-end w-full pt-4 md:items-center md:w-2/3 md:flex-row md:py-0">
                        <a href="#" class="w-full px-6 py-2 mr-0 text-gray-700 md:px-3 md:mr-2 lg:mr-3 md:w-auto">Sign In</a>
                        <a href="#_" class="inline-flex items-center w-full px-5 px-6 py-3 text-sm font-medium leading-4 text-white bg-gray-900 md:w-auto md:rounded-full hover:bg-gray-800 focus:outline-none md:focus:ring-2 focus:ring-0 focus:ring-offset-2 focus:ring-gray-800">Sign Up</a>
                    </div>
                </div>
            </div>
            <div class="absolute right-0 flex flex-col items-center justify-center w-10 h-10 bg-white rounded-full cursor-pointer md:hidden hover:bg-gray-100" id="menuToggleButton">
                <svg class="w-6 h-6 text-gray-700 burger-icon" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg class="w-6 h-6 text-gray-700 close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </nav>
</header>