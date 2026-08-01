<header class="flex items-center justify-between flex-wrap bg-neutral-bg p-5 gap-5 md:py-6 md:pl-[25px] md:pr-[38px] lg:flex-nowrap dark:bg-dark-neutral-bg lg:gap-0"><a class="hidden logo" href="index.html"><img class="md:mr-[100px] lg:mr-[133px]" src="{{ asset('assets/images/icons/icon-logo.svg') }}" alt="Frox logo"></a>
        <div class="bg-gray-100 flex rounded-xl m-0 py-[14px] px-[18px] dark:bg-gray-dark-100 lg:mr-[47px] lg:ml-6 order-last lg:order-first transition-colors hover:bg-gray-200 dark:hover:bg-gray-dark-200">
          <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2 text-gray-500 dark:text-gray-dark-500 hover:text-color-brands font-semibold text-sm w-full h-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            View Website
          </a>
        </div>
        <div class="flex items-center order-2 user-noti gap-[30px] xl:gap-[48px] lg:order-3 lg:mr-0">
          <div class="dropdown dropdown-end hidden">
            <label class="cursor-pointer dropdown-label" tabindex="0">
              <div class="relative w-[26px] h-[26px]"><img class="w-full h-full object-cover" src="{{ asset('assets/images/icons/icon-messages.svg') }}" alt="message icon">
                <div class="w-2 h-2 bg-fuchsia rounded-full absolute right-[1px] top-[-1px]"></div>
              </div>
            </label>
            <ul class="dropdown-content" tabindex="0">
              <div class="relative menu rounded-box dropdown-shadow bg-white px-6 mt-[50px] min-w-[350px] pt-[21px] pb-[11px] dark:bg-dark-neutral-bg">
                <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute border-b-white w-[14px] top-[-7px] right-[18px] dark:border-b-dark-neutral-bg"></div>
                <div class="flex items-center justify-between pb-5 border-b border-neutral mb-[22px] dark:border-b-dark-neutral-border">
                  <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">New Mesages</p><a class="text-color-brands text-xs hover:opacity-75" href="#">View All</a>
                </div>
                <li class="rounded ml-[-14px] hover:bg-gray-100 w-[calc(100%+28px)] dark:hover:bg-gray-dark-100">
                  <div class="flex items-start bg-transparent gap-[10px] p-[14px]"><img class="w-8 h-8 rounded-full" src="{{ asset('assets/images/avatar-layouts-1.png') }}" alt="user avatar">
                    <div> 
                      <div class="flex items-center gap-[25px] mb-[7px]">
                        <p class="leading-4 text-gray-1100 font-semibold text-[10px] dark:text-gray-dark-1100">Esther Howard</p>
                        <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500">3 mins ago</p>
                      </div>
                      <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500 line-clamp-2">Please tell me how to develop this template to Tailwind CSS 3 and ReactJS ?</p>
                    </div>
                  </div>
                </li>
                <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border"></div>
                <li class="rounded ml-[-14px] hover:bg-gray-100 w-[calc(100%+28px)] dark:hover:bg-gray-dark-100">
                  <div class="flex items-start bg-transparent gap-[10px] p-[14px]"><img class="w-8 h-8 rounded-full" src="{{ asset('assets/images/avatar-layouts-2.png') }}" alt="user avatar">
                    <div> 
                      <div class="flex items-center gap-[25px] mb-[7px]">
                        <p class="leading-4 text-gray-1100 font-semibold text-[10px] dark:text-gray-dark-1100">Emma Watson</p>
                        <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500">3 mins ago</p>
                      </div>
                      <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500 line-clamp-2">Hey, I'm going to meet a friend of mine at the department store.</p>
                    </div>
                  </div>
                </li>
                <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border"></div>
                <li class="rounded ml-[-14px] hover:bg-gray-100 w-[calc(100%+28px)] dark:hover:bg-gray-dark-100">
                  <div class="flex items-start bg-transparent gap-[10px] p-[14px]"><img class="w-8 h-8 rounded-full" src="{{ asset('assets/images/avatar-layouts-3.png') }}" alt="user avatar">
                    <div> 
                      <div class="flex items-center gap-[25px] mb-[7px]">
                        <p class="leading-4 text-gray-1100 font-semibold text-[10px] dark:text-gray-dark-1100">Elizabeth</p>
                        <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500">3 mins ago</p>
                      </div>
                      <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500 line-clamp-2">Good morning, How are you? What about our next meeting?</p>
                    </div>
                  </div>
                </li>
              </div>
            </ul>
          </div>
          <div class="dropdown dropdown-end hidden">
            <label class="cursor-pointer dropdown-label" tabindex="0">
              <div class="relative w-[26px] h-[26px]"><img class="w-full h-full object-cover" src="{{ asset('assets/images/icons/icon-notification-bing.svg') }}" alt="notification icon">
                <div class="w-2 h-2 bg-fuchsia rounded-full absolute right-[1px] top-[-1px]"></div>
              </div>
            </label>
            <ul class="dropdown-content" tabindex="0">
              <div class="relative menu rounded-box dropdown-shadow bg-white px-6 mt-[50px] min-w-[350px] pt-[21px] pb-[11px] dark:bg-dark-neutral-bg">
                <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute border-b-white w-[14px] top-[-7px] right-[18px] dark:border-b-dark-neutral-bg"></div>
                <div class="flex items-center justify-between pb-5 border-b border-neutral mb-[22px] dark:border-b-dark-neutral-border">
                  <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">Notifications</p><a class="text-color-brands text-xs hover:opacity-75" href="#">View All</a>
                </div>
                <li class="rounded ml-[-14px] hover:bg-gray-100 w-[calc(100%+28px)] dark:hover:bg-gray-dark-100">
                  <div class="flex items-start bg-transparent gap-[10px] p-[14px]"><img class="w-8 h-8 rounded-full" src="{{ asset('assets/images/avatar-layouts-4.png') }}" alt="user avatar">
                    <div> 
                      <div class="flex items-center gap-[15px] mb-[7px]">
                        <p class="leading-4 text-gray-1100 font-semibold text-[10px] dark:text-gray-dark-1100">Jenny Wilson</p>
                        <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500 line-clamp-2">commented on your latest story</p>
                      </div>
                      <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500">1 min ago</p>
                    </div>
                  </div>
                </li>
                <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border"></div>
                <li class="rounded ml-[-14px] hover:bg-gray-100 w-[calc(100%+28px)] dark:hover:bg-gray-dark-100">
                  <div class="flex items-start bg-transparent gap-[10px] p-[14px]"><img class="w-8 h-8 rounded-full" src="{{ asset('assets/images/avatar-layouts-1.png') }}" alt="user avatar">
                    <div> 
                      <div class="flex items-center gap-[15px] mb-[7px]">
                        <p class="leading-4 text-gray-1100 font-semibold text-[10px] dark:text-gray-dark-1100">Esther Howard</p>
                        <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500 line-clamp-2">commented on your latest story</p>
                      </div>
                      <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500">3 mins ago</p>
                    </div>
                  </div>
                </li>
                <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border"></div>
                <li class="rounded ml-[-14px] hover:bg-gray-100 w-[calc(100%+28px)] dark:hover:bg-gray-dark-100">
                  <div class="flex items-start bg-transparent gap-[10px] p-[14px]"><img class="w-8 h-8 rounded-full" src="{{ asset('assets/images/avatar-layouts-2.png') }}" alt="user avatar">
                    <div> 
                      <div class="flex items-center gap-[15px] mb-[7px]">
                        <p class="leading-4 text-gray-1100 font-semibold text-[10px] dark:text-gray-dark-1100">Steven</p>
                        <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500 line-clamp-2">add new photos in Travel Album</p>
                      </div>
                      <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500">5 mins ago</p>
                    </div>
                  </div>
                </li>
                <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border"></div>
                <li class="rounded ml-[-14px] hover:bg-gray-100 w-[calc(100%+28px)] dark:hover:bg-gray-dark-100">
                  <div class="flex items-start bg-transparent gap-[10px] p-[14px]"><img class="w-8 h-8 rounded-full" src="{{ asset('assets/images/avatar-layouts-3.png') }}" alt="user avatar">
                    <div> 
                      <div class="flex items-center gap-[15px] mb-[7px]">
                        <p class="leading-4 text-gray-1100 font-semibold text-[10px] dark:text-gray-dark-1100">Wada Warren</p>
                        <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500 line-clamp-2">posted new job</p>
                      </div>
                      <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500">6 mins ago</p>
                    </div>
                  </div>
                </li>
                <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border"></div>
                <li class="rounded ml-[-14px] hover:bg-gray-100 w-[calc(100%+28px)] dark:hover:bg-gray-dark-100">
                  <div class="flex items-start bg-transparent gap-[10px] p-[14px]"><img class="w-8 h-8 rounded-full" src="{{ asset('assets/images/avatar-layouts-4.png') }}" alt="user avatar">
                    <div> 
                      <div class="flex items-center gap-[15px] mb-[7px]">
                        <p class="leading-4 text-gray-1100 font-semibold text-[10px] dark:text-gray-dark-1100">Jenny Wilson</p>
                        <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500 line-clamp-2"> Updated her profile and company</p>
                      </div>
                      <p class="leading-4 text-gray-500 text-[10px] dark:text-gray-dark-500">8 mins ago</p>
                    </div>
                  </div>
                </li>
              </div>
            </ul>
          </div>
          <div class="dropdown dropdown-end">
            <label class="cursor-pointer dropdown-label" tabindex="0">
              @if(auth()->user() && auth()->user()->avatar)
                <img src="{{ asset(auth()->user()->avatar) }}" alt="user avatar" class="w-10 h-10 rounded-full object-cover">
              @else
                <img src="{{ asset('assets/images/avatar-layouts-5.png') }}" alt="user avatar" class="w-10 h-10 rounded-full object-cover">
              @endif
            </label>
            <ul class="dropdown-content" tabindex="0">
              <div class="relative menu rounded-box dropdown-shadow p-[25px] pb-[10px] bg-neutral-bg mt-[25px] md:mt-[40px] min-w-[237px] dark:text-gray-dark-500 dark:border-dark-neutral-border dark:bg-dark-neutral-bg">
                <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute w-[14px] top-[-7px] border-b-neutral-bg dark:border-b-dark-neutral-bg right-[18px]"></div>
                <li class="text-gray-500 hover:text-gray-1100 hover:bg-gray-100 dark:text-gray-dark-500 dark:hover:text-gray-dark-1100 dark:hover:bg-gray-dark-100 rounded-lg group p-[15px] pl-[21px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="{{ route('cms.profile.edit') }}"> <i class="w-4 h-4 grid place-items-center"><img class="group-hover:filter-black dark:group-hover:filter-white" src="{{ asset('assets/images/icons/icon-user.svg') }}" alt="icon"></i><span>Profile</span></a>
                </li>
                <li class="text-gray-500 hover:text-gray-1100 hover:bg-gray-100 dark:text-gray-dark-500 dark:hover:text-gray-dark-1100 dark:hover:bg-gray-dark-100 rounded-lg group p-[15px] pl-[21px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <i class="w-4 h-4 grid place-items-center"><img class="group-hover:filter-black dark:group-hover:filter-white" src="{{ asset('assets/images/icons/icon-favorite-chart.svg') }}" alt="icon"></i><span>Dashboard</span></a>
                </li>
                <li class="text-gray-500 hover:text-gray-1100 hover:bg-gray-100 dark:text-gray-dark-500 dark:hover:text-gray-dark-1100 dark:hover:bg-gray-dark-100 rounded-lg group p-[15px] pl-[21px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <i class="w-4 h-4 grid place-items-center"><img class="group-hover:filter-black dark:group-hover:filter-white" src="{{ asset('assets/images/icons/icon-bitcoin-card.svg') }}" alt="icon"></i><span>Payouts</span></a>
                </li>
                <li class="text-gray-500 hover:text-gray-1100 hover:bg-gray-100 dark:text-gray-dark-500 dark:hover:text-gray-dark-1100 dark:hover:bg-gray-dark-100 rounded-lg group p-[15px] pl-[21px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <i class="w-4 h-4 grid place-items-center"><img class="group-hover:filter-black dark:group-hover:filter-white" src="{{ asset('assets/images/icons/icon-trade.svg') }}" alt="icon"></i><span>Statement</span></a>
                </li>
                <li class="text-gray-500 hover:text-gray-1100 hover:bg-gray-100 dark:text-gray-dark-500 dark:hover:text-gray-dark-1100 dark:hover:bg-gray-dark-100 rounded-lg group p-[15px] pl-[21px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <i class="w-4 h-4 grid place-items-center"><img class="group-hover:filter-black dark:group-hover:filter-white" src="{{ asset('assets/images/icons/icon-sun.svg') }}" alt="icon"></i><span>Settings</span></a>
                </li>
                <div class="w-full bg-neutral h-[1px] my-[7px] dark:bg-dark-neutral-border"></div>
                <form id="logout-form" action="{{ route('cms.logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
                <li class="text-gray-500 hover:text-gray-1100 hover:bg-gray-100 dark:text-gray-dark-500 dark:hover:text-gray-dark-1100 dark:hover:bg-gray-dark-100 rounded-lg group p-[15px] pl-[21px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i class="w-4 h-4 grid place-items-center"><img class="group-hover:filter-black dark:group-hover:filter-white" src="{{ asset('assets/images/icons/icon-logout.svg') }}" alt="icon"></i><span>Log out</span></a>
                </li>
              </div>
            </ul>
          </div>
        </div>
      </header>