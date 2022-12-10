import{o as s,c as n,b as a,w as c,F as o,v as f,k as l,a as t,u,I as m,t as i,q as p,i as h,ah as b}from"./app.a2148d35.js";import{_ as g,I as y,a as w}from"./WardSlot.360fbfdb.js";import{I as _}from"./IconUserMd.cbbc3213.js";const k=t("p",{class:"mt-1 italic text-xs text-accent"}," \u0E4F Not in any particular order ",-1),v={class:"w-1/3"},I={class:"w-2/3 mt-1 md:mt-0"},B={class:"block py-1 italic truncate underline"},C={class:"font-semibold text-xs flex items-center"},N={class:"block py-1 italic truncate"},V={class:"font-semibold text-xs flex items-center"},D={class:"block py-1 italic truncate"},q={key:0},F=t("label",{class:"form-label my-2 md:my-4"},"Dialysis at Chronic unit",-1),G={__name:"DialysisSlot",props:{slots:{type:Object,required:!0}},setup(r){return(L,S)=>{var d;return s(),n("div",null,[k,a(b,{name:"flip-list",class:"mt-2 lg:mt-0 grid grid-cols-4 gap-2",tag:"div"},{default:c(()=>[(s(!0),n(o,null,f(r.slots.acute,(e,x)=>(s(),n("div",{class:l(["w-full rounded shadow",{"col-span-3 p-2 md:px-4":e.slot_count===3,"col-span-2 p-2 md:px-4":e.slot_count===2,"col-span-1 p-2 md:px-4":e.slot_count===1,"flex items-center":!e.available,"text-primary bg-green-400 p-8":e.available,"text-complement-darker bg-amber-400":e.status!==void 0&&e.status==="scheduling","text-primary bg-rose-900 animate-pulse":e.status!==void 0&&e.status==="started","text-primary bg-complement":e.status!==void 0&&e.status==="finished","text-primary bg-red-400":e.status!==void 0&&e.status!=="scheduling"&&e.status!=="started"&&e.status!=="finished","border-4 border-white border-dashed":e.extra_slot}]),key:x},[e.available?h("",!0):(s(),n(o,{key:0},[t("div",v,[a(u(m),{href:e.order_route},{default:c(()=>[t("span",{class:l(["p-1 md:p-2 rounded-full text-xs font-semibold underline",{"bg-primary text-accent":e.status!=="submitted","bg-indigo-400 text-white":e.status==="submitted"}])},i(e.type),3)]),_:2},1032,["href"])]),t("div",I,[a(u(m),{class:"font-semibold text-xs flex items-center",href:e.case_record_route},{default:c(()=>[e.on_ventilator?(s(),p(y,{key:0,class:"h-3 w-3 mr-1 text-green-400"})):(s(),p(w,{key:1,class:"h-3 w-3 mr-1 text-white"})),t("span",B,i(e.patient_name),1)]),_:2},1032,["href"]),t("p",C,[a(_,{class:"h-3 w-3 mr-1 text-white"}),t("span",N,i(e.author),1)]),t("p",V,[a(_,{class:"h-3 w-3 mr-1 text-white"}),t("span",D,i(e.attending),1)])])],64))],2))),128))]),_:1}),(d=r.slots.chronic)!=null&&d.length?(s(),n("div",q,[F,a(g,{slots:r.slots.chronic.map(e=>({...e}))},null,8,["slots"])])):h("",!0)])}}};export{G as default};
