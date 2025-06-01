<div class="friends-tab ct-tab album_tab radius-8 bg-white p-3">
    <form method="POST" action="{{ route('profileUpdate', ['id' => $user_info->id]) }}" enctype="multipart/form-data">
        @csrf
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
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? ($user_info->name ?? '') }}">
        </div>
        
        @if (0)
            <!-- Gender -->
            <div class="mb-3">
                <label for="gender" class="form-label">Jinsi</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Tanlang</option>
                    <option @if ($user_info->gender == 'male') selected @endif value="male">Erkak</option>
                    <option @if ($user_info->gender == 'female') selected @endif value="female">Ayol</option>
                </select>
            </div>
    
            <!-- Date of Birth -->
            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Tug‘ilgan sana</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                       value="{{ old('date_of_birth') ?? (isset($user_info->date_of_birth) ? date('Y-m-d', $user_info->date_of_birth) : '') }}">
    
            </div>
            
            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label">Telefon</label>
                <input type="tel" class="form-control" id="phone"
                       name="phone"
                       maxlength="9"
                       placeholder="XX XXX XX XX"
                       value="{{ old('phone') ?? ($user_info->phone ?? '') }}"
                       autocomplete="off" required
                       autofocus
                >
            </div>
            
            <!-- Address -->
            <div class="mb-3" id="fullAddress">
                <label for="address" class="form-label">Manzil</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') ?? ($user_info->address ?? '') }}">
            </div>
            
            <!-- Profession -->
            <div class="mb-3">
                <label for="profession" class="form-label">Kasbi</label>
                <input type="text" class="form-control" id="profession" name="profession" value="{{ old('profession') ?? ($user_info->profession ?? '') }}">
            </div>
        @endif

        <!-- Address -->

        <div class="mb-3" id="fullAddress">
            <label for="address" class="form-label">Manzil</label>
        </div>

        <div class="mb-3">
            <label for="regionId">Viloyat</label>
            <select name="regionId" id="regionId"
                    class="form-select eForm-control select2" required>
                <option value="">Tanlanmadi</option>
                @php
                    $regionId = old('region') ?: $user_info->regionId ?: null;
                @endphp

                @foreach (\App\Models\User::REGIONS as $k => $region)
                    @php
                        $selected = $regionId == $k ? 'selected' : '';
                    @endphp
                    <option value="{{ $k }}" {{ $selected }}> {{ $region }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="districtId">Tuman</label>
            <select name="districtId" id="districtId"
                    class="form-select eForm-control select2"
                    required
                    data-all-distirct="{{ json_encode(\App\Models\User::DISTRICTS) }}"
                    data-selected-distirct="{{ old('districtId') ?: $user_info->districtId ?: null }}"
            >
                <option value="">Tanlanmadi</option>

            </select>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Qo'shimcha ma'lumotlar (mahalla, ko'cha)</label>
            <input type="text" class="form-control" required id="address" name="address" value="{{ old('address') ?? ($user_info->address ?? '') }}">
        </div>

        @if (!empty(request()->sponsor))
            <input type="hidden" name="isSponsor" value="1">
        @endif

        @if (!empty(request()->coast))
            <input type="hidden" name="isSponsor" value="1">
        @endif
    
        <!-- Telegram -->
        <div class="mb-3">
            <label for="telegram" class="form-label">
                <i class="fab fa-telegram-plane fa-lg" style="color: #0088cc;"></i>
                Telegram
            </label>
            <input type="text" class="form-control" id="telegram" name="telegram" placeholder="https://t.me/username"
                   value="{{ old('telegram') ?? ($user_telegram ?? '') }}"
            >
        </div>
    
        <!-- Instagram -->
        <div class="mb-3">
            <label for="instagram" class="form-label">
                <i class="fab fa-instagram fa-lg" style="color: #E1306C;"></i>
                Instagram
            </label>
            <input type="text" class="form-control" id="instagram" name="instagram" placeholder="https://instagram.com/username"
                   value="{{ old('telegram') ?? ($user_instagram ?? '') }}"
            >
        </div>
    
        <!-- Facebook -->
        <div class="mb-3">
            <label for="facebook" class="form-label">
                <i class="fab fa-facebook fa-lg" style="color: #1877F2;"></i>
                Facebook
            </label>
            <input type="text" class="form-control" id="facebook" name="facebook" placeholder="https://facebook.com/username"
                   value="{{ old('facebook') ?? ($user_facebook ?? '') }}">
        </div>
    
        <!-- YouTube -->
        <div class="mb-3">
            <label for="youtube" class="form-label">
                <i class="fab fa-youtube fa-lg" style="color: #FF0000;"></i>
                YouTube
            </label>
            <input type="text" class="form-control" id="youtube" name="youtube" placeholder="https://youtube.com/@username"
                   value="{{ old('youtube') ?? ($user_youtube ?? '') }}">
        </div>
    
        <!-- YouTube -->
        <div class="mb-3">
            <label for="youtube" class="form-label">
                <i class="fas fa-globe fa-lg" style="color: #0d6efd;"></i>
                Site
            </label>
            <input type="text" class="form-control" id="site" name="site" placeholder="https://your.site.com"
                   value="{{ old('site') ?? ($user_site ?? '') }}">
        </div>
    
        <div class="form-check my-4">
            <input class="form-check-input" type="checkbox" name="is_anonymous_sponsor"
                   id="is_anonymous_sponsor"
                   value="1"
                {{ old('is_anonymous_sponsor', $user_info->is_anonymous_sponsor) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_anonymous_sponsor">
                Men xomiy sifatida ishtirok etsam, ismimni ko‘rsatishni xohlamayman
            </label>
        </div>



    
    
            <!-- About -->
        <div class="mb-3">
            <label for="about" class="form-label">O‘zingiz haqingizda</label>
            <textarea class="form-control" id="about" name="about" rows="3">{{ old('about') ?? ($user_info->about ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Saqlash</button>
    </form>
</div>

<script>
    console.log(232)
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('photoPreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const regionSelect = document.getElementById('regionId');
        const districtSelect = document.getElementById('districtId');

        // Agar districtSelect yo‘q bo‘lsa, to‘xtatamiz
        if (!regionSelect || !districtSelect) return;

        const allDistricts = districtSelect.dataset.allDistirct ? JSON.parse(districtSelect.dataset.allDistirct) : {};
        const selectedDistrictId = districtSelect.dataset.selectedDistirct;

        function populateDistricts(regionId) {
            districtSelect.innerHTML = '<option value="">Tanlanmadi</option>';

            if (allDistricts[regionId]) {
                const districts = allDistricts[regionId];
                Object.keys(districts).forEach(function (key) {
                    const option = document.createElement('option');
                    option.value = key;
                    option.textContent = districts[key];
                    if (key === selectedDistrictId) {
                        option.selected = true;
                    }
                    districtSelect.appendChild(option);
                });
            }

            // Agar select2 ishlatilayotgan bo‘lsa
            if (typeof jQuery !== 'undefined' && $(districtSelect).hasClass('select2')) {
                $(districtSelect).trigger('change.select2');
            }
        }

        if (regionSelect.value) {
            populateDistricts(regionSelect.value);
        }

        regionSelect.addEventListener('change', function () {
            console.log(232);
            populateDistricts(this.value);
        });
    });

</script>