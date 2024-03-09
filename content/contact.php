<main>

    <!-- Main Heading -->
    <section class="mx-6 md:px-20 my-10 md:container md:mx-auto text-center">
        <h1 class="font-medium text-3xl">Get In Touch</h1>
        <p class="mt-5 text-gray-600">Questions, comments, or ideas? We’d love to hear from you. Use the form below to send us a message.</p>
    </section>


    <!-- Content -->
    <section>
    
    <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    ?>
        <form method="POST">

            <?php if (isset($_GET['success'])) { ?>
                <div class="text-center text-green-800 mb-3 px-3" role="alert">
                    <strong class="font-bold">Message received! We'll get back to you within a day, but if it's the weekend, we're recharging our superpowers. Patience is a virtue!</strong>
                </div>
            <?php } elseif (isset($_GET['errors'])) { ?>
                <div class="text-center text-red-800 mb-3" role="alert">
                    <strong class="font-bold">Please correct the following errors: <?php echo htmlspecialchars(implode(', ', $_GET['errors'])); ?></strong>
                </div>
            <?php } ?>

            <input type="hidden" name="form_type" value="contact">

            <!-- CSRF token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="mx-2 md:mx-28 px-2 my-10 md:py-10">
                <div class="mb-2 md:w-1/2 mx-auto">
                    <label for="name" class="mb-2 text-sm font-medium">Your Name</label>
                    <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-full">
                </div>
                <div class="mb-2 md:w-1/2 mx-auto">
                    <label for="email" class="mb-2 text-sm font-medium">Your Email</label>
                    <input type="text" id="email" name="email" class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-full">
                </div>
                <div class="mb-2 md:w-1/2 mx-auto">
                    <label for="message" class="mb-2 text-sm font-medium">Message</label>
                    <textarea rows="4" id="message" name="message" class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-full" required></textarea>
                </div>
                <div class="mt-8 text-center">
                    <button class="rounded-lg bg-gray-900 text-white hover:bg-gray-800 font-extralight tracking-wide px-2 py-1 border hover:border-gray-200 border-gray-400 inline-flex items-center" name="submit">
                        Submit
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </div>

        </form>
    </section>

</main>