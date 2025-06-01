
<div class="main_content">
    <!-- Mani section header and breadcrumb -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div
                        class="d-flex justify-content-between align-items-center flex-wrap gr-15"
                >
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('Edit user profile') }}</h4>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('admin.users') }}" class="export_btn" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title="{{ get_phrase('Back') }}">{{ get_phrase('Back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Admin area -->
    <div class="row">
        <div class="col-6">
            <div class="eSection-wrap-2">
                <div class="eForm-layouts">
                    <form method="POST" action="{{ route('admin.user.update', $user_data->id) }}" enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label eForm-label">{{ get_phrase('Name') }}</label>
                            <input type="text" class="form-control eForm-control" value="{{$user_data->name}}" id="name" name="name" placeholder="{{ get_phrase('Name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label eForm-label">{{ get_phrase('Email') }}</label>
                            <input type="email" class="form-control eForm-control" value="{{$user_data->email}}" id="email" name="email" placeholder="{{ get_phrase('Email address') }}">
                        </div>

                        <div class="mb-3">
                            <label for="likeBalance" class="form-label eForm-label">{{ get_phrase('Like Balance') }}</label>
                            <input type="text" class="form-control eForm-control" value="{{ old('likeBalance') ?? ($like_balance->balance ?? null) }}"
                                   id="likeBalance" name="likeBalance"
                                   placeholder="">
                        </div>

                        <div class="mb-3">
                            <label for="walletBalance" class="form-label eForm-label">{{ get_phrase('Wallet Balance') }}</label>
                            <input type="text" class="form-control eForm-control" value="{{ old('walletBalance') ?? ($wallet_balance->balance ?? null) }}"
                                   id="walletBalance" name="walletBalance"
                                   placeholder="">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label eForm-label">{{ get_phrase('Password') }}</label>
                            <input type="text" class="form-control eForm-control" value="" autocomplete="off"
                                   id="password" name="password"
                                   placeholder="*********">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label eForm-label">{{ get_phrase('Phone') }}</label>
                            <input type="text" class="form-control eForm-control" value="{{ $user_data->phone}}" id="phone" name="phone" placeholder="{{ get_phrase('Phone') }}">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label eForm-label">{{ get_phrase('Region') }}</label>
                            @php
                                $region = '';
                                $district = '';

                                if (!empty(\App\Models\User::REGIONS[$user_data->regionId])) {
									$region = \App\Models\User::REGIONS[$user_data->regionId];

									$districts = \App\Models\User::DISTRICTS[$user_data->regionId];

									$district = $districts[$user_data->districtId] ?? '';
                                }
                            @endphp
                            <input type="text" class="form-control eForm-control"
                                   value="{{ $region . ', ' . $district . ' tumani'}}"
                                   id="region" name="region" placeholder="{{ get_phrase('Address') }}">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label eForm-label">{{ get_phrase('Address') }}</label>
                            <input type="text" class="form-control eForm-control" value="{{$user_data->address}}" id="address" name="address" placeholder="{{ get_phrase('Address') }}">
                        </div>

                        <div class="mb-3">
                            <button type="button" id="copyBtn" class="btn btn-sm btn-secondary">
                                Copy address
                            </button>
                        </div>

                        <script>
                            document.getElementById('copyBtn').addEventListener('click', function () {
                                const regionVal = document.getElementById('region').value;
                                const addressVal = document.getElementById('address').value;

                                if (!regionVal) return;

                                navigator.clipboard.writeText(regionVal + ', ' + addressVal);
                            });
                        </script>

                        <div class="mb-3">
                            <label for="gender" class="form-label eForm-label">{{ get_phrase('Gender') }}</label><br>
                            <input type="radio" id="male" name="gender" value="male" <?php if($user_data->gender == 'male') echo 'checked'; ?>> <label for="male">{{ get_phrase('Male') }}</label>
                            <input type="radio" id="female" name="gender" value="female" <?php if($user_data->gender == 'female') echo 'checked'; ?>> <label for="female">{{ get_phrase('Female') }}</label>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label eForm-label">{{ get_phrase('Photo') }}</label>
                            <input type="file" class="form-control eForm-control" id="photo" name="photo" placeholder="{{ get_phrase('Photo') }}">
                        </div>

                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label eForm-label">{{ get_phrase('Date of birth') }}</label>
                            <input type="text" class="form-control eForm-control inputDate" id="date_of_birth" name="date_of_birth" placeholder="{{ get_phrase('Date of birth') }}" value="{{date('m-d-Y', $user_data->date_of_birth)}}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="bio" class="form-label eForm-label">{{ get_phrase('Bio') }}</label>
                            <textarea name="bio" id="bio" class="form-control eForm-control" placeholder="Bio">{{$user_data->about}}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ get_phrase('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Start Footer -->
@include('backend.footer')
<!-- End Footer -->
</div>




