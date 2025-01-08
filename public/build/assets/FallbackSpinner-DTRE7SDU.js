import{R as x,b as n,c as a,d as e,e as w,M as C,s as d,u as c,g as y,h as u,t as s,F as g,m as v,_ as k}from"./app-CC1FPugz.js";const I={role:"alert",class:"border-t-4 rounded-b p-4 shadow-sm bg-white border-yellow-400"},j={class:"flex items-center text-yellow-400"},V={class:"w-1/6 flex justify-center"},N=e("div",{class:"w-5/6 lg:text-lg"},[e("p",{class:"font-semibold"}," COVID-19 Information ")],-1),B={class:"mt-4 md:mt-5 bg-gray-100 rounded shadow p-2 md:p-4"},D={class:"form-label !mb-0"},F={key:0,class:"italic"},R={key:1,class:"italic"},$={class:"text-sm italic text-complement"},E={class:"md:hidden space-y-2"},O={class:"flex justify-between"},z={class:"font-medium text-complement"},M={class:"hidden md:flex space-x-4"},S={class:"font-medium text-complement"},K={__name:"CovidInfo",props:{configs:{type:Object,required:!0}},async setup(i){let r,_;const m=i,f=l=>(console.log(l),{ok:!1}),o=([r,_]=x(()=>window.axios.post(m.configs.route_lab,{hn:m.configs.hn}).then(l=>l.data).catch(f)),r=await r,_(),r);return(l,H)=>{var h,p;return n(),a("div",I,[e("div",j,[e("div",V,[w(C,{class:"w-6 h-6 md:w-10 md:h-10 lg:w-16 lg:h-16"})]),N]),e("div",B,[e("div",{class:d(["flex items-center justify-between pb-2 md:pb-4 xl:pb-8",{"border-b border-accent":(h=c(o).labs)==null?void 0:h.length}])},[e("label",D,[y(" siriraj test : "),c(o).ok?c(o).found?u("",!0):(n(),a("span",R,"No test")):(n(),a("span",F,"ERROR"))]),e("label",$,s(c(o).when),1)],2),(p=c(o).labs)!=null&&p.length?(n(!0),a(g,{key:0},v(c(o).labs,(t,b)=>(n(),a("div",{key:b,class:"py-4 odd:border-t odd:border-b border-dashed last:!border-b-0"},[e("div",E,[e("div",O,[e("p",z,s(t.date_lab),1),e("p",{class:d(["italic",{"text-red-400":t.result==="Detected","text-amber-400":t.result==="Inconclusive"}])},s(t.result),3)]),e("p",null,s(t.name),1)]),e("div",M,[e("p",S,s(t.date_lab),1),e("p",null,s(t.name),1),e("p",{class:d(["italic",{"text-red-400":t.result==="Detected","text-amber-400":t.result==="Inconclusive"}])},s(t.result),3)])]))),128)):u("",!0)])])}}},q={},A={class:"flex justify-end"},L=e("svg",{class:"text-complement animate animate-spin w-5 h-5 my-4",viewBox:"0 0 512 512",fill:"currentColor"},[e("path",{d:"M222.7 32.15C227.7 49.08 218.1 66.9 201.1 71.94C121.8 95.55 64 169.1 64 255.1C64 362 149.1 447.1 256 447.1C362 447.1 448 362 448 255.1C448 169.1 390.2 95.55 310.9 71.94C293.9 66.9 284.3 49.08 289.3 32.15C294.4 15.21 312.2 5.562 329.1 10.6C434.9 42.07 512 139.1 512 255.1C512 397.4 397.4 511.1 256 511.1C114.6 511.1 0 397.4 0 255.1C0 139.1 77.15 42.07 182.9 10.6C199.8 5.562 217.6 15.21 222.7 32.15V32.15z"})],-1),T=[L];function G(i,r){return n(),a("div",A,T)}const P=k(q,[["render",G]]);export{P as F,K as _};