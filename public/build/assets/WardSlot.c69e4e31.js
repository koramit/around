import{_ as p,o as s,c as a,a as t,b as r,w as i,F as d,v as x,k as l,u as _,I as u,t as n,q as m,i as C,ab as b}from"./app.94c97313.js";import{I as h}from"./IconUserMd.4aceb845.js";const g={},y={viewBox:"0 0 448 512"},w=t("path",{fill:"currentColor",d:"M277.37 11.98C261.08 4.47 243.11 0 224 0c-53.69 0-99.5 33.13-118.51 80h81.19l90.69-68.02zM342.51 80c-7.9-19.47-20.67-36.2-36.49-49.52L239.99 80h102.52zM224 256c70.69 0 128-57.31 128-128 0-5.48-.95-10.7-1.61-16H97.61c-.67 5.3-1.61 10.52-1.61 16 0 70.69 57.31 128 128 128zM80 299.7V512h128.26l-98.45-221.52A132.835 132.835 0 0 0 80 299.7zM0 464c0 26.51 21.49 48 48 48V320.24C18.88 344.89 0 381.26 0 422.4V464zm256-48h-55.38l42.67 96H256c26.47 0 48-21.53 48-48s-21.53-48-48-48zm57.6-128h-16.71c-22.24 10.18-46.88 16-72.89 16s-50.65-5.82-72.89-16h-7.37l42.67 96H256c44.11 0 80 35.89 80 80 0 18.08-6.26 34.59-16.41 48H400c26.51 0 48-21.49 48-48v-41.6c0-74.23-60.17-134.4-134.4-134.4z"},null,-1),v=[w];function V(c,o){return s(),a("svg",y,v)}var k=p(g,[["render",V]]);const z={},M={fill:"currentColor",viewBox:"0 0 640 512"},H=t("path",{d:"M320 32C372.1 32 419.7 73.8 454.5 128H584C614.9 128 640 153.1 640 184V269C640 324.1 602.5 372.1 549.1 385.5L477.5 403.4C454.6 433.8 421.1 457.2 384 469.8V393.2C403.6 376.8 416 353.1 416 326.4C416 276.9 372.5 191.1 320 191.1C267 191.1 224 276.9 224 326.4C224 353 236.3 376.9 256 393.3V469.9C217.6 457.4 184.9 433.8 162.2 403.3L90.9 385.5C37.48 372.1 0 324.1 0 269V184C0 153.1 25.07 128 56 128H185.1C219.8 73.8 267.4 32 320 32V32zM56 176C51.58 176 48 179.6 48 184V269C48 302.1 70.49 330.9 102.5 338.9L134.3 346.8C130.2 332.2 127.1 316.7 127.1 300.8C127.1 264.7 139.4 219.2 159.1 176H56zM480.7 176C500.4 219.2 512 264.7 512 300.8C512 316.8 509.8 332.2 505.6 346.9L537.5 338.9C569.5 330.9 592 302.1 592 269V184C592 179.6 588.4 176 584 176H480.7zM288 320C288 302.3 302.3 288 320 288C337.7 288 352 302.3 352 320V512H288V320z"},null,-1),$=[H];function I(c,o){return s(),a("svg",M,$)}var L=p(z,[["render",I]]);const B=t("p",{class:"mt-1 italic text-xs text-accent"}," \u0E4F Not in any particular order ",-1),S={class:"w=1/4"},N={class:"w-3/4 mt-1 mt-0 space-x-2 flex items-center"},j={class:"block py-1 italic truncate underline"},W={class:"font-semibold text-xs flex items-center"},q={class:"block py-1 italic truncate"},A={class:"font-semibold text-xs flex items-center"},F={class:"block py-1 italic truncate"},O={__name:"WardSlot",props:{slots:{type:Array,required:!0}},setup(c){return(o,P)=>(s(),a("div",null,[B,r(b,{name:"flip-list",class:"mt-2 lg:mt-0 grid grid-cols-1 gap-2",tag:"div"},{default:i(()=>[(s(!0),a(d,null,x(c.slots,(e,f)=>(s(),a("div",{class:l(["w-full p-2 md:p-4 rounded shadow",{"bg-green-400 p-4 h-8":!e.type,"flex justify-between items-center":e.type,"text-complement-darker bg-amber-400":e.status!==void 0&&e.status==="scheduling","text-primary bg-complement":e.status!==void 0&&(e.status==="started"||e.status==="finished"),"text-primary bg-red-400":e.status!==void 0&&e.status!=="scheduling"&&e.status!=="started"&&e.status!=="finished","border-4 border-white border-dashed":e.extra_slot}]),key:f},[e.type?(s(),a(d,{key:0},[t("div",S,[r(_(u),{href:e.order_route},{default:i(()=>[t("span",{class:l(["p-1 md:p-2 rounded-full text-xs font-semibold underline",{"bg-primary text-accent":e.status!=="submitted","bg-indigo-400 text-white":e.status==="submitted"}])},n(e.type),3)]),_:2},1032,["href"])]),t("div",N,[r(_(u),{class:"font-semibold text-xs flex items-center",href:e.case_record_route},{default:i(()=>[e.on_ventilator?(s(),m(L,{key:0,class:"h-3 w-3 mr-1 text-green-400"})):(s(),m(k,{key:1,class:"h-3 w-3 mr-1 text-white"})),t("span",j,n(e.patient_name),1)]),_:2},1032,["href"]),t("p",W,[r(h,{class:"h-3 w-3 mr-1 text-white"}),t("span",q,n(e.author),1)]),t("p",A,[r(h,{class:"h-3 w-3 mr-1 text-white"}),t("span",F,n(e.attending),1)])])],64)):C("",!0)],2))),128))]),_:1})]))}};var E=Object.freeze(Object.defineProperty({__proto__:null,default:O},Symbol.toStringTag,{value:"Module"}));export{L as I,E as W,O as _,k as a};
