<x-layout>
    
    <div  class=" px-5 pt-16 w-full items-center overflow-hidden flex flex-col justify-between  h-screen absolute top-0 ">
        <div class=" relative max-w-3xl text-base sm:text-lg md:text-xl overflow-x-hidden shadow-lg bg-blue-50 w-full p-5 rounded-3xl ">
            <div id="back_to_reviewer" class="absolute top-2 right-5 w-4 h-4 p-1 rounded-lg bg-red-200 cursor-pointer hover:bg-red-400">
                <img class="w-3" src="{{asset('logo_icons/x.svg')}}" alt="close">
            </div>
            <div id="cards" >
                {{-- reviewer content goes here --}}
            </div>
        </div>
        <div class="mt-3 text-center h-1-5 mb-4">
            <span id="topic_name" class=" text-gray-700 text-sm">Topic Title Here</span>
            <div class="card_nav  flex justify-center gap-2 items-center mt-1 ">
                <div id="prev" class=" w-10 p-2 bg-gray-300 rounded-md cursor-pointer hover:bg-gray-500" >
                    <img src="{{asset('logo_icons/arrow2.svg')}}" alt="left arrow">
                </div>
                <div class=" font-sans font-semibold text-gray-500">
                    <span id="current_page">
                        {{-- current page in here --}}
                    </span>
                    /
                    <span id="total_page">
                        {{-- total page in herre --}}
                    </span>
                </div>
                <div id="next" class=" w-10 p-2 bg-gray-300 rounded-md cursor-pointer hover:bg-gray-500" >
                    <img src="{{asset('logo_icons/arrow1.svg')}}" alt="right arrow">
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const urlParams = new URLSearchParams(window.location.search);
            const topicId = urlParams.get('topicId');
            const cards_item = document.getElementById('cards');
            const bottomnav = document.getElementById('bottom_nav');
            bottomnav.classList.add('hidden');
            const back_to_reviewer = document.getElementById('back_to_reviewer');
            const total_page = document.getElementById('total_page');
            const current_page = document.getElementById('current_page');
            const prev = document.getElementById('prev');
            const next = document.getElementById('next');
            var page = 0;

            let cards = [];
            fetch('/disect_reviewer',{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({topicId})
            })
            .then( response => response.json())
            .then(data => {
                let content ='';
                if(data.success){
                    item = data['data'];
                    // console.log(item);

                    //assign the fetch data to the local array
                    item.forEach((item) =>{
                        cards.push("<strong>" +  item[0] + "</strong><br>" + " " + item[1]);    
                    })
                    
                    //this show the total number of reviewer
                    total_page.innerHTML = cards.length;
                    current_page.innerHTML = page + 1;

                    //if the card is first dont let the user user prev
                    console.log(page);
                    if(page == 0){
                        //get the card start from 0
                        cards.forEach((item , index)=> {
                            if(index == page){
                                cards_item.innerHTML = item;
                            }
                            
                        });
                        console.log('im 0');
                        prev.classList.remove('hover:bg-gray-500');
                        prev.classList.remove ('cursor-pointer');
                    }
                    //for the prev clicked
                    prev.addEventListener('click', function(){
                        
                        if(page > 0){
                            page -= 1;
                            //update the card
                            cards.forEach((item , index)=> {
                                if(index == page){
                                    cards_item.innerHTML = item;
                                }
                            });
                            //updating if the next button is clickable or not
                            if(page < cards.length -1){
                                next.classList.add('hover:bg-gray-500');
                                next.classList.add ('cursor-pointer');
                            }
                             //updating the current page
                            current_page.innerHTML = page + 1;
                        }else{
                            prev.classList.remove('hover:bg-gray-500');
                            prev.classList.remove ('cursor-pointer');
                        }
                        
                        console.log('prev cliked im page : ', page);
                    });    
                    //add event lister f next is clicked
                    next.addEventListener('click', function(){
                        if(page < cards.length -1){
                            page += 1;
                            //update the card
                            cards.forEach((item , index)=> {
                                if(index == page){
                                    cards_item.innerHTML = item;
                                }
                            });
                            //updating if the prev button is clickable or not
                            if(page == 1){
                                prev.classList.add('hover:bg-gray-500');
                                prev.classList.add ('cursor-pointer');
                            }
                            //updating the current page
                            current_page.innerHTML = page + 1;
                        }else{
                            next.classList.remove('hover:bg-gray-500');
                            next.classList.remove ('cursor-pointer');
                        }
                        console.log(cards.length, ' next cliked, im page : ', page);
                    });    
 
                }

            })
            .catch(error=>{ console.error('error :', error)});


             //click event to go back to reviewerr
             back_to_reviewer.addEventListener('click', function(){
                window.location.href=`/reviewer/${topicId}`;
            });
            
            
        
            

        });

    </script>
</x-layout>