import{h as s,c as i,d as a,e as c,F as m,m as y,s as u,b as e,u as h,K as p,t as r,k as _,g as l,Q as g}from"./app-60f1e3be.js";import{I as k,a as w,_ as f}from"./WardSlot-b58d777e.js";import{I as x}from"./IconUserMd-3757d7a5.js";const v=e("p",{class:"mt-1 italic text-xs text-accent"}," ๏ Not in any particular order ",-1),C={class:"w-1/3"},I={class:"w-2/3 mt-1 md:mt-0"},V={class:"block py-1 italic truncate underline"},B={class:"font-semibold text-xs flex items-center"},D={class:"block py-1 italic truncate"},N={class:"font-semibold text-xs flex items-center"},F={class:"block py-1 italic truncate"},O={key:0},S=e("label",{class:"form-label my-2 md:my-4"},"COVID-19 Cases at Acute unit",-1),j={key:1},q=e("label",{class:"form-label my-2 md:my-4"},"Dialysis at Chronic unit",-1),L={__name:"DialysisSlot",props:{slots:{type:Object,required:!0}},setup(n){return(z,A)=>{var d,o;return s(),i("div",null,[v,a(g,{name:"flip-list",class:"mt-2 lg:mt-0 grid grid-cols-4 gap-2",tag:"div"},{default:c(()=>[(s(!0),i(m,null,y(n.slots.acute,(t,b)=>(s(),i("div",{class:u(["w-full rounded shadow",{"col-span-3 p-2 md:px-4":t.slot_count===3,"col-span-2 p-2 md:px-4":t.slot_count===2,"col-span-1 p-2 md:px-4":t.slot_count===1,"flex items-center":!t.available,"text-primary bg-green-400 p-8":t.available,"text-complement-darker bg-amber-400":t.status!==void 0&&t.status==="scheduling","text-primary bg-rose-900 animate-pulse":t.status!==void 0&&t.status==="started","text-primary bg-complement":t.status!==void 0&&t.status==="finished","text-primary bg-red-400":t.status!==void 0&&t.status!=="scheduling"&&t.status!=="started"&&t.status!=="finished","border-4 border-white border-dashed":t.extra_slot}]),key:b},[t.available?l("",!0):(s(),i(m,{key:0},[e("div",C,[a(h(p),{href:t.order_route},{default:c(()=>[e("span",{class:u(["p-1 md:p-2 rounded-full text-xs font-semibold underline",{"bg-primary text-accent":t.status!=="submitted","bg-indigo-400 text-white":t.status==="submitted"}])},r(t.type),3)]),_:2},1032,["href"])]),e("div",I,[a(h(p),{class:"font-semibold text-xs flex items-center",href:t.case_record_route},{default:c(()=>[t.on_ventilator?(s(),_(k,{key:0,class:"h-3 w-3 mr-1 text-green-400"})):(s(),_(w,{key:1,class:"h-3 w-3 mr-1 text-white"})),e("span",V,r(t.patient_name),1)]),_:2},1032,["href"]),e("p",B,[a(x,{class:"h-3 w-3 mr-1 text-white"}),e("span",D,r(t.author),1)]),e("p",N,[a(x,{class:"h-3 w-3 mr-1 text-white"}),e("span",F,r(t.attending),1)])])],64))],2))),128))]),_:1}),(d=n.slots.covid)!=null&&d.length?(s(),i("div",O,[S,a(f,{slots:n.slots.covid.map(t=>({...t}))},null,8,["slots"])])):l("",!0),(o=n.slots.chronic)!=null&&o.length?(s(),i("div",j,[q,a(f,{slots:n.slots.chronic.map(t=>({...t}))},null,8,["slots"])])):l("",!0)])}}};export{L as default};