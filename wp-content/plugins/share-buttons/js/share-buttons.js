function new_window(url) {
    width_screen = (screen.width-700)/2;
    height_screen = (screen.height-400)/2;
    params = 'menubar=0, toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=0, width=700, height=400, left='+width_screen+', top='+height_screen;
    window.open(url,'newwin', params);
}

var nereidFadeObjects = new Object();

var nereidFadeTimers = new Object();

var opacitiz=0; 



window.onload=function() {

  var e=document.getElementsByTagName('*')

  for (var i=0,l=e.length;i<l;i++) e[i].sourceIndex=i

}



function KrossBrows(object,num) {

        if (num==1)

                if (!document.all) nereidFade(object, 1,50,0.1);

                else nereidFade(object, 100,50,10);

        else

                if (!document.all) nereidFade(object, 0.7,70,0.05);

                else nereidFade(object, 50,70,5);

}



function nereidFade(object, destOp, rate, delta){

        if (!document.all) opacitiz=object.style.opacity;

        else opacitiz=object.filters.alpha.opacity;

       

        clearTimeout(nereidFadeTimers[object.sourceIndex]);

        diff = destOp-opacitiz;

        direction = 1;

        if (opacitiz > destOp) direction = -1;



        delta=Math.min(direction*diff,delta);

        if (!document.all) object.style.opacity=parseFloat(object.style.opacity)+(direction*delta);

        else object.filters.alpha.opacity+=direction*delta;



        if (opacitiz != destOp){

                nereidFadeObjects[object.sourceIndex]=object;

                nereidFadeTimers[object.sourceIndex]=setTimeout("nereidFade(nereidFadeObjects["+object.sourceIndex+"],"+destOp+","+rate+","+delta+")",rate);

        }

}