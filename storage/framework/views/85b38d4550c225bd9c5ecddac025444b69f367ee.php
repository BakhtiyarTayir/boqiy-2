<div class="friends-tab ct-tab album_tab radius-8 bg-white p-3">
    <form method="POST" action="<?php echo e(route('profileUpdate', ['id' => $user_info->id])); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <!-- Preview Image -->
        <div class="mb-3">
            <label class="form-label">Rasm ko‘rinishi:</label><br>
            <img id="photoPreview" class="rounded-circle" src="http://127.0.0.1:8000/storage/userimage/default.png" alt="Preview" width="150" height="150">
        </div>
        <!-- Photo upload -->
        <div class="mb-3">
            <label for="photo" class="form-label">Rasm yuklash</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" onchange="previewImage(event)">
        </div>

        <!-- Username -->
        <div class="mb-3">
            <label for="name" class="form-label">FIO</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name') ?? ($user_info->name ?? '')); ?>">
        </div>
        
        <?php if(0): ?>
            <!-- Gender -->
            <div class="mb-3">
                <label for="gender" class="form-label">Jinsi</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Tanlang</option>
                    <option <?php if($user_info->gender == 'male'): ?> selected <?php endif; ?> value="male">Erkak</option>
                    <option <?php if($user_info->gender == 'female'): ?> selected <?php endif; ?> value="female">Ayol</option>
                </select>
            </div>
    
            <!-- Date of Birth -->
            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Tug‘ilgan sana</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                       value="<?php echo e(old('date_of_birth') ?? (isset($user_info->date_of_birth) ? date('Y-m-d', $user_info->date_of_birth) : '')); ?>">
    
            </div>
            
            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label">Telefon</label>
                <input type="tel" class="form-control" id="phone"
                       name="phone"
                       maxlength="9"
                       placeholder="XX XXX XX XX"
                       value="<?php echo e(old('phone') ?? ($user_info->phone ?? '')); ?>"
                       autocomplete="off" required
                       autofocus
                >
            </div>
            
            <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label">Manzil</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo e(old('address') ?? ($user_info->address ?? '')); ?>">
            </div>
            
            <!-- Profession -->
            <div class="mb-3">
                <label for="profession" class="form-label">Kasbi</label>
                <input type="text" class="form-control" id="profession" name="profession" value="<?php echo e(old('profession') ?? ($user_info->profession ?? '')); ?>">
            </div>
        <?php endif; ?>
    
        <!-- Telegram -->
        <div class="mb-3">
            <label for="telegram" class="form-label">
                <i class="fab fa-telegram-plane fa-lg" style="color: #0088cc;"></i>
                Telegram
            </label>
            <input type="text" class="form-control" id="telegram" name="telegram" placeholder="https://t.me/username"
                   value="<?php echo e(old('telegram') ?? ($user_telegram ?? '')); ?>"
            >
        </div>
    
        <!-- Instagram -->
        <div class="mb-3">
            <label for="instagram" class="form-label">
                <i class="fab fa-instagram fa-lg" style="color: #E1306C;"></i>
                Instagram
            </label>
            <input type="text" class="form-control" id="instagram" name="instagram" placeholder="https://instagram.com/username"
                   value="<?php echo e(old('telegram') ?? ($user_instagram ?? '')); ?>"
            >
        </div>
    
        <!-- Facebook -->
        <div class="mb-3">
            <label for="facebook" class="form-label">
                <i class="fab fa-facebook fa-lg" style="color: #1877F2;"></i>
                Facebook
            </label>
            <input type="text" class="form-control" id="facebook" name="facebook" placeholder="https://facebook.com/username"
                   value="<?php echo e(old('facebook') ?? ($user_facebook ?? '')); ?>">
        </div>
    
        <!-- YouTube -->
        <div class="mb-3">
            <label for="youtube" class="form-label">
                <i class="fab fa-youtube fa-lg" style="color: #FF0000;"></i>
                YouTube
            </label>
            <input type="text" class="form-control" id="youtube" name="youtube" placeholder="https://youtube.com/@username"
                   value="<?php echo e(old('youtube') ?? ($user_youtube ?? '')); ?>">
        </div>
    
        <!-- YouTube -->
        <div class="mb-3">
            <label for="youtube" class="form-label">
                <i class="fas fa-globe fa-lg" style="color: #0d6efd;"></i>
                Site
            </label>
            <input type="text" class="form-control" id="site" name="site" placeholder="https://your.site.com"
                   value="<?php echo e(old('site') ?? ($user_site ?? '')); ?>">
        </div>
    
        <div class="form-check my-4">
            <input class="form-check-input" type="checkbox" name="is_anonymous_sponsor"
                   id="is_anonymous_sponsor"
                   value="1"
                <?php echo e(old('is_anonymous_sponsor', $user_info->is_anonymous_sponsor) ? 'checked' : ''); ?>>
            <label class="form-check-label" for="is_anonymous_sponsor">
                Men xomiy sifatida ishtirok etsam, ismimni ko‘rsatishni xohlamayman
            </label>
        </div>
    
    
            <!-- About -->
        <div class="mb-3">
            <label for="about" class="form-label">O‘zingiz haqingizda</label>
            <textarea class="form-control" id="about" name="about" rows="3"><?php echo e(old('about') ?? ($user_info->about ?? '')); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Saqlash</button>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('photoPreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script><?php /**PATH /home/asilbek/Downloads/boqiy-uz/boqiy.uz-master/web/resources/views/frontend/profile/editProfile.blade.php ENDPATH**/ ?>