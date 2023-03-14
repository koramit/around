import{y as F,c as o,b as t,F as c,m as l,d as i,u as k,v as p,h as s,t as r,k as _,e as y,E as A,g as j,B as q}from"./app-dd899f9b.js";import{u as B}from"./useConfirmForm-6f529404.js";import{_ as b,I as L,a as D}from"./IconDoubleDown-b6191004.js";import{I as H}from"./IconUserMd-37f0be69.js";import{_ as I}from"./PaginationNav-14c25dce.js";const M={class:"bg-white rounded shadow overflow-x-auto hidden md:block"},N={class:"w-full whitespace-nowrap"},T={class:"text-left font-semibold text-complement"},E=["textContent","colspan"],O=["textContent"],S={class:"border-t"},V={key:1,class:"p-2 flex justify-center items-center"},R=["innerHTML"],$={class:"md:hidden"},P={class:"flex justify-between items-center my-2 px-2"},U={class:"bg-primary-darker p-2 rounded-full"},z={class:"my-2 p-2 bg-gray-100 rounded space-y-2"},G={class:"flex justify-between items-center"},J=["innerHTML"],K={class:"font-semibold text-complement text-xs flex items-center"},Q={class:"block italic truncate"},W={class:"flex justify-center items-center"},X={class:"italic text-center w-full"},ne={__name:"SlotRequestIndex",props:{requests:{type:Object,required:!0},configs:{type:Object,required:!0},endpoints:{type:Object,required:!0}},setup(d){const x=F(()=>q(()=>import("./ConfirmFormComposable-d5d7810d.js"),["assets/ConfirmFormComposable-d5d7810d.js","assets/FormInput-56cb1a84.js","assets/app-dd899f9b.js","assets/app-34900f54.css","assets/SpinnerButton-88430bad.js","assets/ModalDialog-223ec98a.js","assets/ModalDialog-10546d4c.css"])),{confirmForm:v,openConfirmForm:w,confirmed:C}=B();let a=null;const u=n=>{switch(n.name){case"approve-request":p({approve:!0}).patch(n.route);break;case"disapprove-request":case"cancel-request":w(n.config),a={...n};break;default:return}},g=n=>{switch(a.name){case"disapprove-request":p({approve:!1,reason:n}).patch(a.route,{preserveState:!1,onFinish:()=>a=null});break;case"cancel-request":p({reason:n}).delete(a.route,{preserveState:!1,onFinish:()=>a=null});break;default:return}};return(n,f)=>(s(),o("div",null,[t("div",M,[t("table",N,[t("tr",T,[(s(),o(c,null,l(["HN","Name","Request","By"],e=>t("th",{class:"px-6 pt-6 pb-4",key:e,textContent:r(e),colspan:e==="MD"?2:1},null,8,E)),64))]),(s(!0),o(c,null,l(d.requests.data,(e,m)=>(s(),o("tr",{class:"focus-within:bg-primary-darker",key:m},[(s(),o(c,null,l(["hn","patient_name","request","requester"],h=>t("td",{class:"px-6 py4 border-t",key:h,textContent:r(e[h])},null,8,O)),64)),t("td",S,[e.actions.length?(s(),_(b,{key:0,actions:e.actions,onActionClicked:u},null,8,["actions"])):(s(),o("div",V,[t("span",{innerHTML:e.status},null,8,R)]))])]))),128))])]),t("div",$,[(s(!0),o(c,null,l(d.requests.data,(e,m)=>(s(),o("div",{class:"bg-white rounded shadow my-4 p-4",key:m},[t("div",P,[t("div",null," HN: "+r(e.hn)+" "+r(e.patient_name),1),e.actions.length>1?(s(),_(A,{key:0},{default:y(()=>[t("div",U,[i(L,{class:"w-4 h-4 text-accent"})])]),dropdown:y(()=>[i(D,{actions:e.actions,onActionClicked:u},null,8,["actions"])]),_:2},1024)):e.actions.length===1?(s(),_(b,{key:1,actions:e.actions,onActionClicked:u},null,8,["actions"])):j("",!0)]),t("div",z,[t("div",G,[t("span",{innerHTML:e.status},null,8,J),t("p",K,[i(H,{class:"h-3 w-3 mr-1"}),t("span",Q,r(e.requester),1)])]),t("div",W,[t("p",X,r(e.request),1)])])]))),128))]),i(I,{links:d.requests.links},null,8,["links"]),i(k(x),{ref_key:"confirmForm",ref:v,onConfirmed:f[0]||(f[0]=e=>k(C)(e,g))},null,512)]))}};export{ne as default};
