
   <div class="page-wrap">
   
       <div class="g-3 blog-cards" >
           <div class="row" id="batchdatashow"> 
               <div class="col-12  h-100 my-1 single-item-countable" id="batch">
                   <article class="single-entry batch-entry  h-100 p-0">
                       <div class="entry-txt p-8">
                           @php 
                              $user_info = App\Models\User::where('id', auth()->user()->id)->first();
                           @endphp
                           <div class="batch E_new_batch">
                            <h4>{{get_phrase('Confirm and pay')}}</h4>
                            <p>{{get_phrase('You are subscribing to Meta Verified on Sociopro.')}}</p>
                               <div class="demo-badge E_badge">
                                   <div class="badge-image  d-flex">
                                       <img src="{{ get_user_image(auth()->user()->id,'optimized') }}" alt="">
                                       <div>
                                            <div class="badge_info d-flex">
                                                <h5>{{$user_info->name}}</h5>
                                                <p class="mb-0"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M11.1825 1.16051C11.5808 0.595046 12.4192 0.595047 12.8175 1.16051L13.8489 2.62463C14.1272 3.01962 14.648 3.15918 15.0865 2.95624L16.7118 2.20397C17.3395 1.91343 18.0655 2.33261 18.1277 3.02149L18.2889 4.80515C18.3324 5.28634 18.7137 5.66763 19.1948 5.71111L20.9785 5.87226C21.6674 5.9345 22.0866 6.66054 21.796 7.28825L21.0438 8.91352C20.8408 9.35198 20.9804 9.87284 21.3754 10.1511L22.8395 11.1825C23.405 11.5808 23.405 12.4192 22.8395 12.8175L21.3754 13.8489C20.9804 14.1272 20.8408 14.648 21.0438 15.0865L21.796 16.7118C22.0866 17.3395 21.6674 18.0655 20.9785 18.1277L19.1948 18.2889C18.7137 18.3324 18.3324 18.7137 18.2889 19.1948L18.1277 20.9785C18.0655 21.6674 17.3395 22.0866 16.7117 21.796L15.0865 21.0438C14.648 20.8408 14.1272 20.9804 13.8489 21.3754L12.8175 22.8395C12.4192 23.405 11.5808 23.405 11.1825 22.8395L10.1511 21.3754C9.87284 20.9804 9.35198 20.8408 8.91352 21.0438L7.28825 21.796C6.66054 22.0866 5.9345 21.6674 5.87226 20.9785L5.71111 19.1948C5.66763 18.7137 5.28634 18.3324 4.80515 18.2889L3.02149 18.1277C2.33261 18.0655 1.91343 17.3395 2.20397 16.7117L2.95624 15.0865C3.15918 14.648 3.01962 14.1272 2.62463 13.8489L1.16051 12.8175C0.595046 12.4192 0.595047 11.5808 1.16051 11.1825L2.62463 10.1511C3.01962 9.87284 3.15918 9.35198 2.95624 8.91352L2.20397 7.28825C1.91343 6.66054 2.33261 5.9345 3.02149 5.87226L4.80515 5.71111C5.28634 5.66763 5.66763 5.28634 5.71111 4.80515L5.87226 3.02149C5.9345 2.33261 6.66054 1.91343 7.28825 2.20397L8.91352 2.95624C9.35198 3.15918 9.87284 3.01962 10.1511 2.62463L11.1825 1.16051Z" fill="#329CE8"/>
                                                    <path d="M7.5 11.83L10.6629 14.9929L17 8.66705" stroke="white" stroke-width="1.67647" stroke-linecap="round" stroke-linejoin="round"/>
                                                  </svg></p>
                                            </div>
                                            <p class="socio-text">{{get_phrase('Sociopro')}}</p>
                                       </div>
                                   </div>
                                   <h4>{{get_phrase("You'll be billed")}} {{currency(get_settings('badge_price'))}} {{get_phrase('per month.')}}</h4>

                               </div>
                               <h3>{{get_phrase('What you get with your subscription.')}}</h3>
                               <form action="{{route('badge.payment_configuration',['id' =>  auth()->user()->id ])}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <ul class="entry_badge">
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        <div class="entry_badge_text">
                                            <label for="title">{{get_phrase('A verified badge')}}</label>
                                            <input id="title" type="hidden" name="title"  value="A verified badge" required>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        <div class="entry_badge_text">
                                            <label for="description">{{get_phrase('Increased account protection')}}</label>
                                            <input id="description" type="hidden" name="description"  value="Increased account protection" required>
                                        </div>
                                    </li>
                                </ul>
                                <button type="submit" class="btn common_btn w-100">{{get_phrase('Pay Now')}}</button>
                              </form>
                       </div>
                   </article>
               </div>
           </div>
       </div>
   </div> <!-- Page Wrap End -->