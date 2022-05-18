const daysAvailable = [];

var elems = $(".filter-section");
elems.sort(function(a, b) {
    return a.getAttribute('data-sort') > b.getAttribute('data-sort')
}).appendTo(elems.parent());

$.fn.dataStartsWith = function(p) {
    var pCamel = p.replace(/-([a-z])/ig, function(m,$1) { return $1.toUpperCase(); });
    return this.filter(function(i, el){
        return Object.keys(el.dataset).some(function(v){
        return v.indexOf(pCamel) > -1;
        });
    });
};

var classNames = [];
$('div[class*="filters-"]').each(function(i, el){
    var name = (el.className.match(/(^|\s)(filters\-[^\s]*)/) || [,,''])[2];
    if(name){
        classNames.push(name);
    }
});


//DATEPICKER
function getDays(){
    daysAvailable.splice(0,daysAvailable.length);
    $(".corso").each(function(){
        if(!$(this).hasClass("uk-hidden")){
            var splittedDate = $(this).attr("tag-calendario").split("-");
            var date = new Date(parseInt(splittedDate[0]), parseInt(splittedDate[1])-1,parseInt(splittedDate[2])).toDateString();

            if(daysAvailable.findIndex((d) => d === date) === -1){
                daysAvailable.push(date);
            }
        }
    });
}

$(document).ready(function(){
    if($('#txtDate').length){
        getDays();
        $('#txtDate').datepicker({
            showButtonPanel: true,
            closeText: 'Svuota',
            onClose: function (dateText, inst) {
                if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
                    document.getElementById(this.id).value = '';
                    checkCorsi();
                }
            },
            beforeShowDay: function( date ) {
                var highlight = daysAvailable.find(d => d === date.toDateString());
                if( highlight ) {
                    return [true, "event", "Uno o più corsi sono disponibili in questo giorno"]; 
                } else {
                    return [false, ''];
                }
            },
            onSelect: ()=>checkCorsi()
        });
    }
});
//FINE SEZIONE DATEPICKER



function checkCorsi(){
    //Populating the array with all the courses, this is used to see which courses match the attributes
    const corsiDaMostrare = [];

    $(".corso").each(function(){
        corsiDaMostrare.push({corso: $(this).attr('id'), ok:true});
    });
    $(".filters :checkbox:checked").each(function() {
        //Check match of attributes
        for (let i = 0; i < classNames.length; i++) {
            let tag = classNames[i].replace('filters','tag');
            let tagValue = $(this).attr(tag);

            if(tagValue){
                $(".corso").each(function(){
                    let attrFound = false;

                    
                    if($(this).attr(tag)){
                        const selectedCorso = $(this);
                        let corsoTagValues = selectedCorso.attr(tag).split(",");
                        for(let j = 0; j<corsoTagValues.length;j++){
                            let name = corsoTagValues[j].trim().replaceAll(" ","-");
                            if(tagValue === name){
                                attrFound=true;
                            }
                        }

                        if(!attrFound){
                            let corsoIndex = corsiDaMostrare.findIndex(function(elem){
                                if(elem.corso === selectedCorso.eq(0).attr('id')){
                                    return elem;
                                }
                            });
                            if(corsoIndex !== -1){
                                corsiDaMostrare[corsoIndex].ok = false;
                            }else{
                                console.log("ERRORE - INDICE IN RICERCA NON TROVATO");
                            }
                        }
                    }

                })
            }
        }
    });

    if($('#txtDate').length){
        var date =$("#txtDate").val();
        if(date){
            var dataDatepicker = date.split("/");
            if(dataDatepicker[1][0]==="0"){
                dataDatepicker[1] = dataDatepicker[1].substring(1);
            }
            var joinedDataDatepicker = dataDatepicker[2]+"-"+dataDatepicker[0]+"-"+dataDatepicker[1];

            $(".corso").each(function(){
                if($(this).attr("tag-calendario")!==joinedDataDatepicker){
                    var selectedCorso = $(this);
                    let corsoIndex = corsiDaMostrare.findIndex(function(elem){
                        if(elem.corso === selectedCorso.eq(0).attr('id')){
                            return elem;
                        }
                    });
                        if(corsoIndex !== -1){
                            corsiDaMostrare[corsoIndex].ok = false;
                        }else{
                            console.log("ERRORE - INDICE IN RICERCA NON TROVATO");
                        }
                }
            });
        }
    }
    
    for(let i = 0; i < corsiDaMostrare.length; i++){
        var element = document.getElementById(corsiDaMostrare[i].corso);

        //Hide courses that don't match the filters and hide the filters with 0 courses shown 
        if(corsiDaMostrare[i].ok === false){
            if(!element.classList.contains('uk-hidden')){
                element.classList.add('uk-hidden');
                classNames.forEach(function(value){
                    let tagPrefix = value.replaceAll("filters","tag");
                    if($(element).attr(tagPrefix) !== undefined){
                        let currentTags=$(element).attr(tagPrefix).split(", ");
                        currentTags.forEach(function(value){
                            value = value.replaceAll(" ","-");                            
                            $('.filters :checkbox').each(function(){
                                if($(this).attr(tagPrefix)==value.trim()){
                                    var label = $("label[for='"+this.id+"']");
                                    var span = label.children('span');
                                    var spanValue = parseInt(span.text())-1;
                                    span.text(spanValue);
                                    if(spanValue === 0){
                                        label.addClass("uk-hidden");
                                    }
                                }
                            });
                        })
                    }else{
                        console.log({element, tagPrefix})

                    }
                    
                });
            }

        //Show courses and filters
        }else{
            if(element.classList.contains('uk-hidden')){
                element.classList.remove('uk-hidden');
                classNames.forEach(function(value){
                    let tagPrefix = value.replace("filters","tag");
                    if($(element).attr(tagPrefix) !== undefined){
                        let currentTags=$(element).attr(tagPrefix).split(", ");
                        currentTags.forEach(function(value){
                            value = value.replaceAll(" ","-");
                            
                            $('.filters :checkbox').each(function(){
                                if($(this).attr(tagPrefix)==value.trim()){
                                    var label = $("label[for='"+this.id+"']");
                                    var span = label.children('span');
                                    var spanValue = parseInt(span.text())+1;
                                    span.text(spanValue);
                                    if(spanValue > 0){
                                        label.removeClass("uk-hidden");
                                    }
                                }
                            });
                        })
                    }else{
                        console.log({element, tagPrefix})
                    }
                });
            }
        }
    }
    if($('#txtDate').length){
        getDays();
    }

}

$(".filters :checkbox").click(function() {
    checkCorsi();
});


//INIZIO PARTE ORDINAMENTO DELLA TABELLA TRAMITE DATA
var dates = [];
$(".corso").each(function(i){
    dates.push({data: $(this).attr("tag-calendario"), elem: $(this)});
});

//BUBBLE SORT -> dates[] = date in ordine + indice dell'elemento DOM
let swapped = true;
do {
    swapped = false;
    for (let j = 0; j < dates.length -1; j++) {
        var d1 = new Date(dates[j].data).getTime();

        var d2 = new Date(dates[j+1].data).getTime();
        if (d1 > d2) {
            let temp = dates[j];
            dates[j] = dates[j+1];
            dates[j+1] = temp;
            swapped = true;
        }
    }
} while (swapped);

var ordState = "asc";
//funzione richiamata dall'onclick sulla tabella
function sortDate(){
    if(ordState ==="asc"){
        $("tbody").empty();
        for (let index = 0; index < dates.length; index++) {
            $("tbody").append(dates[index].elem);
        }
        $("#down-arrow").attr("fill","black");
        $("#up-arrow").attr("fill","#a8a7b7");
        ordState = "dec";
    }else{
        $("tbody").empty();
        for (let index = dates.length-1; index >= 0; index--) {
            $("tbody").append(dates[index].elem);
        }
        $("#down-arrow").attr("fill","#a8a7b7");
        $("#up-arrow").attr("fill","black");
        ordState="asc"
    }
}
sortDate(ordState);
//FINE PARTE ORDINAMENTO DELLA TABELLA TRAMITE DATA

//INIZIO INSERIMENTO DELL'ICONA PER IL SORT DELLA DATA
$("#head-date").append('<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 330 330" style="height:13px; margin-left: 5px;"><path fill="#a8a7b7" id="up-arrow" d="M100.606,100.606L150,51.212V315c0,8.284,6.716,15,15,15c8.284,0,15-6.716,15-15V51.212l49.394,49.394 C232.322,103.535,236.161,105,240,105c3.839,0,7.678-1.465,10.606-4.394c5.858-5.857,5.858-15.355,0-21.213l-75-75c-5.857-5.858-15.355-5.858-21.213,0l-75,75c-5.858,5.857-5.858,15.355,0,21.213C85.251,106.463,94.749,106.463,100.606,100.606z"/></svg>');
$("#head-date").append('<svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 330 330" style="height:13px; transform: rotate(180deg);" ><path fill="black" id="down-arrow" d="M100.606,100.606L150,51.212V315c0,8.284,6.716,15,15,15c8.284,0,15-6.716,15-15V51.212l49.394,49.394 C232.322,103.535,236.161,105,240,105c3.839,0,7.678-1.465,10.606-4.394c5.858-5.857,5.858-15.355,0-21.213l-75-75c-5.857-5.858-15.355-5.858-21.213,0l-75,75c-5.858,5.857-5.858,15.355,0,21.213C85.251,106.463,94.749,106.463,100.606,100.606z"/></svg>');
