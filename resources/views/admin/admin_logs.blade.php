<x-admin_layout>
    <main>
        {{-- //================================================================= --}}

    <div class="relative flex size-full min-h-screen flex-col bg-slate-50 group/design-root overflow-x-hidden" style='font-family: Inter, "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <div class="px-3 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
            <div class="flex flex-wrap justify-between gap-3 p-4">
              <div class="flex min-w-72 flex-col gap-3">
                <p class="text-[#0e141b] tracking-light text-[32px] font-bold leading-tight">Activity logs</p>
                <p class="text-[#4e7397] text-sm font-normal leading-normal">See all the latest activity across your account</p>
              </div>
            </div>
            <div class="px-4 py-3">
              <label class="flex flex-col min-w-40 h-12 w-full">
                <div class="flex w-full flex-1 items-stretch rounded-xl h-full">
                  <div
                    class="text-[#4e7397] flex border-none bg-[#e7edf3] items-center justify-center pl-4 rounded-l-xl border-r-0"
                    data-icon="MagnifyingGlass"
                    data-size="24px"
                    data-weight="regular"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"
                      ></path>
                    </svg>
                  </div>
                  <input
                    placeholder="Search activity feed"
                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#0e141b] focus:outline-0 focus:ring-0 border-none bg-[#e7edf3] focus:border-none h-full placeholder:text-[#4e7397] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                    value=""
                  />
                </div>
              </label>
            </div>
            <div class="px-4 py-3 @container">
              <div class="flex overflow-hidden rounded-xl border border-[#d0dbe7] bg-slate-50">
                <table class="flex-1">
                  <thead>
                    <tr class="bg-slate-50">
                      <th class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 px-4 py-3 text-left text-[#0e141b] w-[400px] text-sm font-medium leading-normal">Name</th>
                      <th class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 px-4 py-3 text-left text-[#0e141b] w-[400px] text-sm font-medium leading-normal">Action</th>
                      <th class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 px-4 py-3 text-left text-[#0e141b] w-[400px] text-sm font-medium leading-normal">Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="border-t border-t-[#d0dbe7]">
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0e141b] text-sm font-normal leading-normal">Admin</td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        Updated billing details
                      </td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        3 days ago
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#d0dbe7]">
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0e141b] text-sm font-normal leading-normal">Admin</td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        Changed account owner
                      </td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        5 days ago
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#d0dbe7]">
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0e141b] text-sm font-normal leading-normal">Admin</td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        Turned off weekly summary emails
                      </td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        1 week ago
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#d0dbe7]">
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0e141b] text-sm font-normal leading-normal">User</td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        Sent feedback
                      </td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        2 weeks ago
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#d0dbe7]">
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0e141b] text-sm font-normal leading-normal">User</td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        Submitted a roadmap idea
                      </td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        3 weeks ago
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#d0dbe7]">
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0e141b] text-sm font-normal leading-normal">User</td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        Voted on a roadmap idea
                      </td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        1 month ago
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#d0dbe7]">
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0e141b] text-sm font-normal leading-normal">User</td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        Commented on a roadmap idea
                      </td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        6 months ago
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#d0dbe7]">
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0e141b] text-sm font-normal leading-normal">User</td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        Signed up for an account
                      </td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        1 year ago
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#d0dbe7]">
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0e141b] text-sm font-normal leading-normal">User</td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        Activated their account
                      </td>
                      <td class="table-31cdc95a-c6eb-468f-938c-0492a53bb70b-column-360 h-[72px] px-4 py-2 w-[400px] text-[#4e7397] text-sm font-normal leading-normal">
                        2 years ago
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
          </div>
        </div>
      </div>
    </div>


        {{-- //========================================================================== --}}
    </main>
</x-admin_layout>