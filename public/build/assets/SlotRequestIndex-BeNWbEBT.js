const __vite__mapDeps=(i,m=__vite__mapDeps,d=(m.f||(m.f=["assets/ConfirmFormComposable-Db-4HLkn.js","assets/FormInput-D9w0L8ZP.js","assets/app-DsPwriMT.js","assets/app-Bymfh7a-.css","assets/SpinnerButton-2tJsTxoc.js","assets/ModalDialog-C-D3KnkS.js","assets/ModalDialog-D8Qet6-I.css"])))=>i.map(i=>d[i]);
import{c as o,b as t,F as c,l,d as i,u as k,T as p,h as s,t as r,j as _,e as y,G as F,g as j,D as A,E as q}from"./app-DsPwriMT.js";import{u as D}from"./useConfirmForm-D187mkLE.js";import{_ as b}from"./ActionColumn-3eJ2nGaJ.js";import{I as L,_ as B}from"./IconDoubleDown-DR7UBF9y.js";import{I as H}from"./IconUserMd-wxBt6Rik.js";import{_ as I}from"./PaginationNav-uZQxv_dV.js";const M={class:"bg-white rounded shadow overflow-x-auto hidden md:block"},N={class:"w-full whitespace-nowrap"},T={class:"text-left font-semibold text-complement"},E=["textContent","colspan"],O=["textContent"],S={class:"border-t"},V={key:1,class:"p-2 flex justify-center items-center"},R=["innerHTML"],$={class:"md:hidden"},P={class:"flex justify-between items-center my-2 px-2"},G={class:"bg-primary-darker p-2 rounded-full"},U={class:"my-2 p-2 bg-gray-100 rounded space-y-2"},z={class:"flex justify-between items-center"},J=["innerHTML"],K={class:"font-semibold text-complement text-xs flex items-center"},Q={class:"block italic truncate"},W={class:"flex justify-center items-center"},X={class:"italic text-center w-full"},oe={__name:"SlotRequestIndex",props:{requests:{type:Object,required:!0},configs:{type:Object,required:!0},endpoints:{type:Object,required:!0}},setup(d){const x=A(()=>q(()=>import("./ConfirmFormComposable-Db-4HLkn.js"),__vite__mapDeps([0,1,2,3,4,5,6]))),{confirmForm:v,openConfirmForm:w,confirmed:C}=D();let a=null;const u=n=>{switch(n.name){case"approve-request":p({approve:!0}).patch(n.route);break;case"disapprove-request":case"cancel-request":w(n.config),a={...n};break;default:return}},g=n=>{switch(a.name){case"disapprove-request":p({approve:!1,reason:n}).patch(a.route,{preserveState:!1,onFinish:()=>a=null});break;case"cancel-request":p({reason:n}).delete(a.route,{preserveState:!1,onFinish:()=>a=null});break;default:return}};return(n,f)=>(s(),o("div",null,[t("div",M,[t("table",N,[t("thead",null,[t("tr",T,[(s(),o(c,null,l(["HN","Name","Request","By"],e=>t("th",{class:"px-6 pt-6 pb-4",key:e,textContent:r(e),colspan:e==="MD"?2:1},null,8,E)),64))])]),(s(!0),o(c,null,l(d.requests.data,(e,m)=>(s(),o("tr",{class:"focus-within:bg-primary-darker",key:m},[(s(),o(c,null,l(["hn","patient_name","request","requester"],h=>t("td",{class:"px-6 py4 border-t",key:h,textContent:r(e[h])},null,8,O)),64)),t("td",S,[e.actions.length?(s(),_(b,{key:0,actions:e.actions,onActionClicked:u},null,8,["actions"])):(s(),o("div",V,[t("span",{innerHTML:e.status},null,8,R)]))])]))),128))])]),t("div",$,[(s(!0),o(c,null,l(d.requests.data,(e,m)=>(s(),o("div",{class:"bg-white rounded shadow my-4 p-4",key:m},[t("div",P,[t("div",null," HN: "+r(e.hn)+" "+r(e.patient_name),1),e.actions.length>1?(s(),_(F,{key:0},{default:y(()=>[t("div",G,[i(L,{class:"w-4 h-4 text-accent"})])]),dropdown:y(()=>[i(B,{actions:e.actions,onActionClicked:u},null,8,["actions"])]),_:2},1024)):e.actions.length===1?(s(),_(b,{key:1,actions:e.actions,onActionClicked:u},null,8,["actions"])):j("",!0)]),t("div",U,[t("div",z,[t("span",{innerHTML:e.status},null,8,J),t("p",K,[i(H,{class:"h-3 w-3 mr-1"}),t("span",Q,r(e.requester),1)])]),t("div",W,[t("p",X,r(e.request),1)])])]))),128))]),i(I,{links:d.requests.links},null,8,["links"]),i(k(x),{ref_key:"confirmForm",ref:v,onConfirmed:f[0]||(f[0]=e=>k(C)(e,g))},null,512)]))}};export{oe as default};