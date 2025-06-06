
<div class="main_content">
    <!-- Mani section header and breadcrumb -->
    <div class="mainSection-title">
      <div class="row">
        <div class="col-12">
          <div
            class="d-flex justify-content-between align-items-center flex-wrap gr-15"
          >
            <div class="d-flex flex-column">
              <h4>{{ get_phrase('All Group') }}</h4>
              
            </div>

            <div class="export-btn-area">
              <a href="{{ route('admin.group.create') }}" class="export_btn"><i class="fas fa-plus me-2"></i> {{ get_phrase('Create') }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Start Admin area -->
    <div class="row">
      <div class="col-12">
        <div class="eSection-wrap-2">
          <!-- Filter area -->
          <div class="table-responsive">
            <table class="table eTable " id="">
              <thead>
                <tr>
                  <th scope="col">{{ get_phrase('Sl No') }}</th>
                  <th scope="col">{{ get_phrase('Group') }}</th>
                  <th scope="col">{{ get_phrase('Group owner') }}</th>
                  <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                   @foreach($groups as $key=>$group)
                        <tr>
                          <th scope="row">
                          <p class="row-number">{{ ++$key }}</p>
                          </th>
                          <td>
                          <div class="dAdmin_info_name min-w-100px">
                              <a href="{{route('single.page', $group->id)}}" class="text-dark" target="_blank">{{ $group->title }}</a>
                          </div>
                          </td>
                          <td>
                          <div class="dAdmin_info_name min-w-100px">
                              <a href="{{route('user.profile.view', $group->getUser->id)}}" class="text-dark" target="_blank">{{ $group->getUser->name ?? "" }}</a>
                              <br><small>{{$group->getUser->email}}</small>
                          </div>
                          </td>
                          
                          <td class="text-center">
                            <div class="adminTable-action me-auto">
                              <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" data-bs-toggle="dropdown" aria-expanded="false">
                                {{get_phrase('Actions')}}
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                <li><a class="dropdown-item" target="_blank" href="{{route('single.group', $group->id)}}">{{get_phrase('View on frontend')}}</a></li>
                                <li><a class="dropdown-item" href="{{route('admin.group.edit', $group->id)}}">{{get_phrase('Edit')}}</a></li>
                                <li><a class="dropdown-item" onclick="return confirm('{{get_phrase('Are You Sure Want To Delete?')}}')" href="{{route('admin.group.delete',['id' => $group->id])}}">{{get_phrase('Delete')}}</a></li>
                              </ul>
                            </div>
                          </td>
                      </tr>
                    @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- End Admin area -->

   
    <!-- Start Footer -->
    @include('backend.footer')
    <!-- End Footer -->
  </div>



