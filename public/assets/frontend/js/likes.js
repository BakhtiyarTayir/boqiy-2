// Инициализация AJAX для отправки CSRF-токена
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add click handler for like buttons to store the context
    $(document).on('click', '.like-item', function(e) {
        window.lastClickedLikeButton = e.target.closest('.like-item');
        console.log('Like button clicked, stored reference');
    });
});

$('.btn-gift-like').on('click', function (e) {
    let $giftLikes = $('#' + $(this).data('giftId'));

    if ($giftLikes) {
        $giftLikes.removeClass('d-none');
    } else {
        $('#redirectLikeBalanceModal').modal('show');
    }

    e.preventDefault();
})

$('.lenta-posts .like-boxes .like-item').on('click', function (e) {
    $(this).closest('.like-boxes').addClass('d-none');
})

$('.btn-show-gift-modal').on('click', function () {
    $('#likesLimitModal').modal('hide');

    const target = $('.like-boxes').first();
    console.log(13, target.html());
    if (target.length) {
        target.removeClass('d-none').focus();
    } else {
        $('#redirectLikeBalanceModal').modal('show');
    }

    target.removeClass('d-none');
    target.focus();
});

// Функция для отображения панели лайков
function showLikesPanel(postId) {
    // Скрываем все открытые панели лайков
    $('.likes-panel').hide();

    // Отображаем панель для текущего поста
    $('#likes-panel-' + postId).toggle();

    // Закрытие панели при клике вне её
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.likes-panel, .gift-button').length) {
            $('.likes-panel').hide();
            $(document).off('click');
        }
    });
}

// Функция для проверки баланса и отправки лайка
function sendLike(postId, likeTypeId, likePrice) {
    console.log('Sending like for post', postId, 'with type', likeTypeId, 'and price', likePrice);

    // Store the clicked button element
    window.lastClickedLikeButton = event ? event.target : null;

    // Проверяем баланс пользователя через AJAX
    $.ajax({
        url: '/check-like-balance',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            like_type_id: likeTypeId,
            price: likePrice,
            post_id: postId // Add post_id to the request
        },
        success: function(response) {
            console.log('Balance check response:', response);

            if (response.success) {
                // Если баланс достаточен, отправляем лайк
                sendLikeToPost(postId, likeTypeId);
            } else {
                // Если баланса недостаточно, показываем модальное окно
                showInsufficientBalanceModal();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error checking balance:', error, xhr.responseText);
            $.toast({
                content: 'Произошла ошибка при проверке баланса',
                position: 'top-right',
                hideAfter: 3000
            });
        }
    });
}

// Функция для отправки лайка на пост
function sendLikeToPost(postId, likeTypeId) {
    $.ajax({
        url: '/send-like',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            post_id: postId,
            like_type_id: likeTypeId
        },
        success: function(response) {
            if (response.success) {
                // Отображаем анимацию лайка над постом
                // Check which property is available in the response
                const animationUrl = response.like_url || response.animation;

                // Check if we have multiple animations
                const allAnimations = response.animations || [];

                // Show the animation with all animations
                showLikeAnimation(postId, animationUrl, allAnimations);

                // Скрываем панель лайков
                $('.likes-panel').hide();

                // Обновляем счетчик лайков, если необходимо
                updateLikeCounter(postId);

                // Show success message
                $.toast({
                    content: response.message || 'Лайк успешно отправлен',
                    position: 'top-right',
                    hideAfter: 3000
                });
            } else {
                $.toast({
                    content: response.message,
                    position: 'top-right',
                    hideAfter: 3000
                });
            }
        },
        error: function() {
            $.toast({
                content: 'Произошла ошибка при отправке лайка',
                position: 'top-right',
                hideAfter: 3000
            });
        }
    });
}

// Функция для отображения анимации лайка над постом
function showLikeAnimation(postId, likeUrl, allAnimations) {
    console.log('Showing animation for post', postId, 'with URL', likeUrl);

    // If allAnimations is provided, use it, otherwise use the single likeUrl
    let animations = [];

    // Process allAnimations if provided
    if (allAnimations) {
        if (Array.isArray(allAnimations)) {
            animations = allAnimations;
        } else if (typeof allAnimations === 'string') {
            // If it's a string, it might be a single URL or comma-separated
            if (allAnimations.includes(',')) {
                animations = allAnimations.split(',').filter(url => url.trim() !== '');
            } else {
                animations = [allAnimations];
            }
        }
    }

    // If no animations were processed, use the likeUrl
    if (animations.length === 0 && likeUrl) {
        animations = [likeUrl];
    }

    console.log('All animations:', animations);

    // Находим контейнер поста - try different selectors with more specific targeting
    let postContainer = $('#post-' + postId);
    console.log('First selector attempt:', postContainer.length ? 'found' : 'not found');

    // If not found, try alternative selectors
    if (!postContainer.length) {
        postContainer = $('.post-item[data-post-id="' + postId + '"]');
        console.log('Second selector attempt:', postContainer.length ? 'found' : 'not found');
    }

    // If still not found, try by class with data attribute
    if (!postContainer.length) {
        postContainer = $('.single-item-countable[data-post-id="' + postId + '"]');
        console.log('Third selector attempt:', postContainer.length ? 'found' : 'not found');
    }

    // If still not found, try by any element with the post ID as data attribute
    if (!postContainer.length) {
        postContainer = $('[data-post-id="' + postId + '"]');
        console.log('Fourth selector attempt:', postContainer.length ? 'found' : 'not found');
    }

    // If still not found, use the stored clicked button if available
    if (!postContainer.length && window.lastClickedLikeButton) {
        const clickedButton = $(window.lastClickedLikeButton);
        postContainer = clickedButton.closest('.post-content, .post-item, .post, .single-item-countable');
        console.log('Fifth selector attempt (from stored button):', postContainer.length ? 'found' : 'not found');
    }

    // If still not found, use the current gift button's parent
    if (!postContainer.length) {
        // Try to find the active gift button
        const clickedButton = $('.gift-button:focus, .gift-button:active').first();
        if (clickedButton.length) {
            postContainer = clickedButton.closest('.post-content, .post-item, .post, .single-item-countable');
            console.log('Sixth selector attempt (from active button):', postContainer.length ? 'found' : 'not found');
        }
    }

    // If all else fails, use the body
    if (!postContainer.length) {
        console.warn('Could not find post container, using body as fallback');
        postContainer = $('body');
    }

    console.log('Found post container:', postContainer);

    // Make sure the container has position relative for absolute positioning of the animation
    if (postContainer.css('position') !== 'relative' && postContainer.css('position') !== 'absolute') {
        postContainer.css('position', 'relative');
    }

    // Create the temporary animation element (will disappear)
    const likeAnimation = $('<div class="like-animation"><img src="' + likeUrl + '" alt="Like"></div>');

    // Add the temporary animation to the container
    postContainer.append(likeAnimation);

    // Check if a permanent animation container already exists
    let permanentAnimationContainer = postContainer.find('.permanent-like-animation');

    if (0 && permanentAnimationContainer.length === 0) {
        // Create the permanent animation container if it doesn't exist
        permanentAnimationContainer = $('<div class="permanent-like-animation"></div>');
        postContainer.append(permanentAnimationContainer);

        // Initialize the animation queue for this post
        postContainer.data('animationQueue', []);
        postContainer.data('currentAnimationIndex', 0);
        postContainer.data('isRotating', false);
    }

    // Get the current animation queue
    let animationQueue = postContainer.data('animationQueue') || [];

    // Clear existing queue if we have a new set of animations and it's explicitly provided
    if (allAnimations && animations.length > 0) {
        animationQueue = [];
    }

    // Add animations to the queue, avoiding duplicates
    animations.forEach(function(anim) {
        if (anim && anim.trim() !== '' && !animationQueue.includes(anim)) {
            animationQueue.push(anim.trim());
        }
    });

    // Update the animation queue
    postContainer.data('animationQueue', animationQueue);

    console.log('Animation queue updated. Queue length:', animationQueue.length);

    // Start the rotation if it's not already running
    if (!postContainer.data('isRotating') && animationQueue.length > 0) {
        rotateAnimations(postContainer);
    }

    // Log that the animation was added
    console.log('Animations added to container');

    // Remove only the temporary animation after it completes
    setTimeout(function() {
        likeAnimation.remove();
        console.log('Temporary animation removed');
    }, 2000);

    // Store the permanent animation in the database
    savePermanentAnimation(postId, likeUrl);

    // Reset the stored button
    window.lastClickedLikeButton = null;
}

// Function to rotate animations
function rotateAnimations(postContainer) {
    console.log('Starting rotation for container:', postContainer);

    postContainer.data('isRotating', true);

    const animationQueue = postContainer.data('animationQueue');
    let currentIndex = postContainer.data('currentAnimationIndex');

    console.log('Animation queue:', animationQueue, 'Current index:', currentIndex);

    if (!animationQueue || animationQueue.length === 0) {
        console.log('No animations to rotate, stopping');
        postContainer.data('isRotating', false);
        return;
    }

    // Get the permanent animation container
    const permanentAnimationContainer = postContainer.find('.permanent-like-animation');
    console.log('Found animation container:', permanentAnimationContainer.length ? 'yes' : 'no');

    // Clear the container
    permanentAnimationContainer.empty();

    // Get the current animation URL
    let currentUrl = animationQueue[currentIndex];
    console.log('Current URL before processing:', currentUrl);

    // Make sure we don't have a comma-separated URL (which would be an error)
    if (currentUrl && currentUrl.includes(',')) {
        console.log('Found comma in URL, splitting:', currentUrl);

        // If we accidentally have a comma-separated string, split it and update the queue
        const splitUrls = currentUrl.split(',').filter(url => url.trim() !== '');

        // Replace the current animation queue with the split URLs
        const newQueue = [...animationQueue];
        newQueue.splice(currentIndex, 1, ...splitUrls);

        // Update the animation queue
        postContainer.data('animationQueue', newQueue);

        // Use the first URL from the split
        currentUrl = splitUrls[0];

        console.log('Fixed comma-separated URL. New queue:', newQueue, 'New current URL:', currentUrl);
    }

    // Add the current animation
    if (currentUrl) {
        console.log('Adding animation to container:', currentUrl);
        const animationElement = $('<img src="' + currentUrl + '" alt="Like">');
        permanentAnimationContainer.append(animationElement);

        console.log('Showing animation:', currentUrl);
    } else {
        console.log('No valid URL to display');
    }

    // Move to the next animation after 15 seconds
    setTimeout(function() {
        console.log('Rotating to next animation');

        // Get the updated animation queue
        const updatedQueue = postContainer.data('animationQueue');
        console.log('Updated queue:', updatedQueue);

        if (updatedQueue && updatedQueue.length > 0) {
            // Increment the index, wrapping around if necessary
            currentIndex = (currentIndex + 1) % updatedQueue.length;
            postContainer.data('currentAnimationIndex', currentIndex);

            console.log('New index:', currentIndex);

            // Continue rotation
            rotateAnimations(postContainer);
        } else {
            // If the queue is empty, stop rotating
            console.log('Queue is empty, stopping rotation');
            postContainer.data('isRotating', false);
        }
    }, 15000); // 15 seconds
}

// Функция для отображения модального окна с недостаточным балансом
function showInsufficientBalanceModal() {
    // Получаем базовый URL сайта
    const baseUrl = window.location.origin;
    // Формируем полный URL для загрузки модального окна
    const url = baseUrl + '/insufficient-balance-modal';

    showCustomModal(url, 'Недостаточно баланса');
}

// Функция для обновления счетчика лайков
function updateLikeCounter(postId) {
    $.ajax({
        url: '/get-post-likes-count/' + postId,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#post-likes-count-' + postId).text(response.count);

                // If we have animations, update them
                if (response.has_animations && response.animations) {
                    // Process the animations from the response
                    let animations = [];

                    if (Array.isArray(response.animations)) {
                        animations = response.animations.filter(url => url && url.trim() !== '');
                    } else if (typeof response.animations === 'string' && response.animations.trim() !== '') {
                        // If it's a string, it might be a single URL or comma-separated
                        if (response.animations.includes(',')) {
                            animations = response.animations.split(',').filter(url => url && url.trim() !== '');
                        } else {
                            animations.push(response.animations.trim());
                        }
                    }

                    // Only proceed if we have valid animations
                    if (animations.length > 0) {
                        // Find the post container
                        let postContainer = $('#post-' + postId);
                        if (!postContainer.length) {
                            postContainer = $('.post-item[data-post-id="' + postId + '"]');
                        }
                        if (!postContainer.length) {
                            postContainer = $('.single-item-countable[data-post-id="' + postId + '"]');
                        }
                        if (!postContainer.length) {
                            postContainer = $('[data-post-id="' + postId + '"]');
                        }

                        if (postContainer.length) {
                            // Make sure the container has position relative
                            if (postContainer.css('position') !== 'relative' && postContainer.css('position') !== 'absolute') {
                                postContainer.css('position', 'relative');
                            }

                            // Create the permanent animation container if it doesn't exist
                            let permanentAnimationContainer = postContainer.find('.permanent-like-animation');
                            if (0 && permanentAnimationContainer.length === 0) {
                                permanentAnimationContainer = $('<div class="permanent-like-animation"></div>');
                                postContainer.append(permanentAnimationContainer);
                            }

                            // Update the animation queue
                            postContainer.data('animationQueue', animations);
                            postContainer.data('currentAnimationIndex', 0);

                            console.log('Updated animations for post', postId, ':', animations);

                            // Start rotation if not already running
                            if (!postContainer.data('isRotating')) {
                                rotateAnimations(postContainer);
                            }
                        }
                    }
                }
            }
        }
    });
}

// Функция для сохранения постоянной анимации в базе данных
function savePermanentAnimation(postId, likeUrl) {
    // Make sure we have a valid URL
    if (!likeUrl || typeof likeUrl !== 'string' || likeUrl.trim() === '') {
        console.error('Invalid animation URL:', likeUrl);
        return;
    }

    // Make sure we don't have a comma in the URL (which would cause issues)
    if (likeUrl.includes(',')) {
        console.error('Animation URL contains commas, which is not allowed:', likeUrl);
        return;
    }

    // Make an AJAX request to save the animation URL
    $.ajax({
        url: '/save-post-animation',
        type: 'POST',
        data: {
            post_id: postId,
            animation_url: likeUrl.trim(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Animation saved successfully:', response);

            // If we have animations in the response, update the queue
            if (response.success && response.animations && response.animations.length > 0) {
                // Find the post container
                let postContainer = $('#post-' + postId);
                if (!postContainer.length) {
                    postContainer = $('.post-item[data-post-id="' + postId + '"]');
                }
                if (!postContainer.length) {
                    postContainer = $('.single-item-countable[data-post-id="' + postId + '"]');
                }
                if (!postContainer.length) {
                    postContainer = $('[data-post-id="' + postId + '"]');
                }

                if (postContainer.length) {
                    // Update the animation queue
                    postContainer.data('animationQueue', response.animations);

                    console.log('Updated animation queue from save response:', response.animations);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error saving animation:', error);
        }
    });
}

// Load permanent animations when the page loads
$(document).ready(function() {
    console.log('Initializing animations on page load');

    // Check if posts have post_animation attribute and add the permanent animation
    $('.single-item-countable, .post-item, .post, [data-post-id]').each(function() {
        const postContainer = $(this);
        const postId = postContainer.data('post-id') || postContainer.attr('id')?.replace('postIdentification', '') || '';
        const postAnimation = postContainer.data('post-animation') || '';

        console.log('Processing post:', postId, 'Animation:', postAnimation);

        // Skip if no post ID
        if (!postId) {
            console.log('Skipping post with no ID');
            return;
        }

        // Initialize the animation queue for this post
        postContainer.data('animationQueue', []);
        postContainer.data('currentAnimationIndex', 0);
        postContainer.data('isRotating', false);

        // If the post has a permanent animation from the server-side rendering
        if (postAnimation && postAnimation !== '') {
            console.log('Post has animation:', postAnimation);

            // Make sure the container has position relative
            if (postContainer.css('position') !== 'relative' && postContainer.css('position') !== 'absolute') {
                postContainer.css('position', 'relative');
            }

            // Create the permanent animation container if it doesn't exist
            let permanentAnimationContainer = postContainer.find('.permanent-like-animation');
            if (0 && permanentAnimationContainer.length === 0) {
                permanentAnimationContainer = $('<div class="permanent-like-animation"></div>');
                postContainer.append(permanentAnimationContainer);
            } else {
                // Clear any existing content
                permanentAnimationContainer.empty();
            }

            // Add the animation to the queue
            let animationQueue = [];

            // The post_animation might contain multiple URLs separated by commas
            const animations = postAnimation.split(',');
            animations.forEach(function(animUrl) {
                if (animUrl && animUrl.trim() !== '') {
                    animationQueue.push(animUrl.trim());
                }
            });

            // Set the animation queue
            postContainer.data('animationQueue', animationQueue);

            console.log('Post ID:', postId, 'Animation Queue:', animationQueue);

            // Start the rotation if there are animations
            if (animationQueue.length > 0 && !postContainer.data('isRotating')) {
                rotateAnimations(postContainer);
            }
        } else {
            // If no animations from server-side, try to fetch them
            fetchPostAnimations(postId, postContainer);
        }
    });
});

// Function to fetch animations for a post
function fetchPostAnimations(postId, postContainer) {
    console.log('Fetching animations for post:', postId);

    $.ajax({
        url: '/get-post-likes-count/' + postId,
        type: 'GET',
        success: function(response) {
            console.log('Received response for post', postId, ':', response);

            if (response.success && response.has_animations && response.animations) {
                console.log('Post has animations in response');

                // Make sure we have valid animations
                let animations = [];

                // Process the animations from the response
                if (Array.isArray(response.animations)) {
                    console.log('Animations is an array');
                    animations = response.animations.filter(url => url && url.trim() !== '');
                } else if (typeof response.animations === 'string' && response.animations.trim() !== '') {
                    console.log('Animations is a string:', response.animations);

                    // If it's a string, it might be a single URL or comma-separated
                    if (response.animations.includes(',')) {
                        console.log('String contains commas, splitting');
                        animations = response.animations.split(',').filter(url => url && url.trim() !== '');
                    } else {
                        console.log('String is a single URL');
                        animations.push(response.animations.trim());
                    }
                }

                console.log('Processed animations:', animations);

                // Only proceed if we have valid animations
                if (animations.length > 0) {
                    console.log('Have valid animations, setting up container');

                    // Make sure the container has position relative
                    if (postContainer.css('position') !== 'relative' && postContainer.css('position') !== 'absolute') {
                        postContainer.css('position', 'relative');
                    }

                    // Create the permanent animation container if it doesn't exist
                    let permanentAnimationContainer = postContainer.find('.permanent-like-animation');
                    if (0 && permanentAnimationContainer.length === 0) {
                        console.log('Creating new animation container');
                        permanentAnimationContainer = $('<div class="permanent-like-animation"></div>');
                        postContainer.append(permanentAnimationContainer);
                    } else {
                        console.log('Using existing animation container');
                        // Clear any existing content
                        permanentAnimationContainer.empty();
                    }

                    // Update the animation queue
                    postContainer.data('animationQueue', animations);
                    postContainer.data('currentAnimationIndex', 0);

                    console.log('Fetched animations for post', postId, ':', animations);

                    // Start rotation if not already running
                    if (!postContainer.data('isRotating')) {
                        console.log('Starting rotation');
                        rotateAnimations(postContainer);
                    } else {
                        console.log('Rotation already running');
                    }
                } else {
                    console.log('No valid animations after processing');
                }
            } else {
                console.log('No animations in response or response not successful');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching animations:', error, xhr);
        }
    });
}