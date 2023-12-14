<div class="flex">
    <div class="w-1/4">
        <div class="relative mb-2">
            @if ($step == 0)
                <div class="w-10 h-10 mx-auto bg-green-500 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-gray-600 w-full">
                        <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             width="24" height="24"><path fill-rule="evenodd" class="heroicon-ui"
                                                          d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z"/></svg>
                    </span>
                </div>
            @elseif ($step > 0)
                <div class="w-10 h-10 mx-auto bg-green-500 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-white w-full">
                        <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             width="24" height="24"><path class="heroicon-ui"
                                                          d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg>
                    </span>
                </div>
            @else
                <div
                    class="w-10 h-10 mx-auto bg-white border-2 border-gray-200 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-gray-600 w-full">
                        <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             width="24" height="24"><path fill-rule="evenodd" class="heroicon-ui"
                                                          d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z"/></svg>
                    </span>
                </div>
            @endif
        </div>
        <div class="text-xs text-center md:text-base">Start</div>
    </div>


    <div class="w-1/4">
        <div class="relative mb-2">
            <div class="absolute flex align-center items-center align-middle content-center"
                 style="width: calc(100% - 2.5rem - 1rem); top: 50%; transform: translate(-50%, -50%)">
                <div class="w-full bg-gray-200 rounded items-center align-middle align-center flex-1">
                    <div class="w-0 bg-green-300 py-1 rounded" style="width: {{$step1percentage}}%;"></div>
                </div>
            </div>
            @if ($step == 1)
                <div class="w-10 h-10 mx-auto bg-green-500 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-gray-600 w-full">
                       <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            width="24" height="24"><path class="heroicon-ui" stroke-linecap="round"
                                                         stroke-linejoin="round" stroke-width="2"
                                                         d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                </div>
            @elseif ($step > 1)
                <div class="w-10 h-10 mx-auto bg-green-500 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-white w-full">
                        <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             width="24" height="24"><path class="heroicon-ui"
                                                          d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg>
                    </span>
                </div>
            @else
                <div
                    class="w-10 h-10 mx-auto bg-white border-2 border-gray-200 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-gray-600 w-full">
                        <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             width="24" height="24"><path class="heroicon-ui" stroke-linecap="round"
                                                          stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                </div>
            @endif
        </div>
        <div class="text-xs text-center md:text-base">Modify Weeks</div>
    </div>

    <div class="w-1/4">
        <div class="relative mb-2">
            <div class="absolute flex align-center items-center align-middle content-center"
                 style="width: calc(100% - 2.5rem - 1rem); top: 50%; transform: translate(-50%, -50%)">
                <div class="w-full bg-gray-200 rounded items-center align-middle align-center flex-1">
                    <div class="w-0 bg-green-300 py-1 rounded" style="width: {{$step2percentage}}%;"></div>
                </div>
            </div>
            @if ($step == 2)
                <div class="w-10 h-10 mx-auto bg-green-500 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-gray-600 w-full">
                       <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            width="24" height="24"><path class="heroicon-ui" stroke-linecap="round"
                                                         stroke-linejoin="round" stroke-width="2"
                                                         d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                </div>
            @elseif ($step > 2)
                <div class="w-10 h-10 mx-auto bg-green-500 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-white w-full">
                        <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             width="24" height="24"><path class="heroicon-ui"
                                                          d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg>
                    </span>
                </div>
            @else
                <div
                    class="w-10 h-10 mx-auto bg-white border-2 border-gray-200 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-gray-600 w-full">
                    <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                         height="24"><path class="heroicon-ui" stroke-linecap="round" stroke-linejoin="round"
                                           stroke-width="2"
                                           d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                </div>
            @endif
        </div>
        <div class="text-xs text-center md:text-base">Modify Workouts</div>
    </div>

    <div class="w-1/4">
        <div class="relative mb-2">
            <div class="absolute flex align-center items-center align-middle content-center"
                 style="width: calc(100% - 2.5rem - 1rem); top: 50%; transform: translate(-50%, -50%)">
                <div class="w-full bg-gray-200 rounded items-center align-middle align-center flex-1">
                    <div class="w-0 bg-green-300 py-1 rounded" style="width: {{$step3percentage}}%;"></div>
                </div>
            </div>

            @if ($step >= 3)
                <div class="w-10 h-10 mx-auto bg-green-500 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-white w-full">
                        <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             width="24" height="24"><path class="heroicon-ui"
                                                          d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg>
                    </span>
                </div>
            @else
                <div
                    class="w-10 h-10 mx-auto bg-white border-2 border-gray-200 rounded-full text-lg text-white flex items-center">
                    <span class="text-center text-gray-600 w-full">
                        <svg class="w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             width="24" height="24"><path class="heroicon-ui"
                                                          d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg>
                    </span>
                </div>
            @endif
        </div>
        <div class="text-xs text-center md:text-base">Finished</div>
    </div>
</div>
