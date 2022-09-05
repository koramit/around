import{r as H,E as O,o as s,c as n,a as t,b as o,w as l,t as r,F as a,v as y,K as B,y as q,_ as L,W as N,g as S,s as A,f as D,i as C,u,X as E,k as $,L as m,q as w,d as T}from"./app.3be387f2.js";import{t as F,p as P}from"./throttle.75d5d27d.js";import{I as M}from"./IconDoubleRight.a769866c.js";import{I as z}from"./IconUserMd.1489a033.js";import{_ as K}from"./PaginationNav.3722863b.js";import"./debounce.bb3aa42d.js";const U={class:"flex items-center w-full md:w-auto"},W=["value"],X={class:"flex justify-end form-input md:w-auto !border-l-0 !rounded-l-none"},G={class:"flex items-center cursor-pointer select-none group"},J=t("div",null,"Scope : ",-1),Q={class:"group-hover:text-accent-darker focus:text-accent-darker mr-1 whitespace-no-wrap"},Y={class:"text-complement group-hover:text-accent-darker focus:text-accent-darker"},Z=t("div",{class:"p-1 rounded-full bg-white hover:bg-primary transition-colors ease-in-out duration-200"},[t("svg",{class:"w-3 h-3 text-accent group-hover:text-accent-darker focus:text-accent-darker",viewBox:"0 0 320 512"},[t("path",{fill:"currentColor",d:"M143 256.3L7 120.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0L313 86.3c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.4 9.5-24.6 9.5-34 .1zm34 192l136-136c9.4-9.4 9.4-24.6 0-33.9l-22.6-22.6c-9.4-9.4-24.6-9.4-33.9 0L160 352.1l-96.4-96.4c-9.4-9.4-24.6-9.4-33.9 0L7 278.3c-9.4 9.4-9.4 24.6 0 33.9l136 136c9.4 9.5 24.6 9.5 34 .1z"})])],-1),R={class:"mt-2 py-2 shadow-xl bg-white text-complement cursor-pointer rounded text-sm"},ee=["onClick"],te={__name:"SearchIndex",props:{scopes:{type:Array,default:()=>[]},form:{type:Object,required:!0}},emits:["searchChanged","scopeChanged"],setup(c,{expose:_}){const k=c,v=H(null);return O(()=>k.form,F(function(h){let i=P(h);i=Object.keys(i).length?i:{remember:"forget"},q.Inertia.get(location.pathname,i,{preserveState:!0})},800),{deep:!0}),_({focus:()=>v.value.focus()}),(h,i)=>(s(),n("div",U,[t("input",{class:"form-input md:w-auto !border-r-0 !rounded-r-none",type:"text",name:"search",onInput:i[0]||(i[0]=d=>h.$emit("searchChanged",d.target.value)),value:c.form.search,placeholder:"search...",autocomplete:"off",ref_key:"searchInput",ref:v},null,40,W),t("div",X,[o(B,null,{default:l(()=>[t("div",G,[J,t("div",Q,[t("span",Y,r(c.form.scope),1)]),Z])]),dropdown:l(()=>[t("div",R,[(s(!0),n(a,null,y(c.scopes.filter(d=>d!==c.form.scope),(d,x)=>(s(),n("button",{class:"block w-full text-left font-semibold px-6 py-2 transition-colors duration-200 ease-out hover:bg-primary hover:text-accent-darker",key:x,onClick:p=>h.$emit("scopeChanged",d)},r(d),9,ee))),128))])]),_:1})])]))}},se={},ne={viewBox:"0 0 576 512"},oe=t("path",{fill:"currentColor",d:"M402.3 344.9l32-32c5-5 13.7-1.5 13.7 5.7V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h273.5c7.1 0 10.7 8.6 5.7 13.7l-32 32c-1.5 1.5-3.5 2.3-5.7 2.3H48v352h352V350.5c0-2.1.8-4.1 2.3-5.6zm156.6-201.8L296.3 405.7l-90.4 10c-26.2 2.9-48.5-19.2-45.6-45.6l10-90.4L432.9 17.1c22.9-22.9 59.9-22.9 82.7 0l43.2 43.2c22.9 22.9 22.9 60 .1 82.8zM460.1 174L402 115.9 216.2 301.8l-7.3 65.3 65.3-7.3L460.1 174zm64.8-79.7l-43.2-43.2c-4.1-4.1-10.8-4.1-14.8 0L436 82l58.1 58.1 30.9-30.9c4-4.2 4-10.8-.1-14.9z"},null,-1),ce=[oe];function re(c,_){return s(),n("svg",ne,ce)}var V=L(se,[["render",re]]);const ae={},le={viewBox:"0 0 448 512"},ie=t("path",{fill:"currentColor",d:"M336 292v24c0 6.6-5.4 12-12 12h-76v76c0 6.6-5.4 12-12 12h-24c-6.6 0-12-5.4-12-12v-76h-76c-6.6 0-12-5.4-12-12v-24c0-6.6 5.4-12 12-12h76v-76c0-6.6 5.4-12 12-12h24c6.6 0 12 5.4 12 12v76h76c6.6 0 12 5.4 12 12zm112-180v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"},null,-1),de=[ie];function ue(c,_){return s(),n("svg",le,de)}var j=L(ae,[["render",ue]]);const _e={},he={viewBox:"0 0 576 512"},me=t("path",{fill:"currentColor",d:"M528.3 46.5H388.5c-48.1 0-89.9 33.3-100.4 80.3-10.6-47-52.3-80.3-100.4-80.3H48c-26.5 0-48 21.5-48 48v245.8c0 26.5 21.5 48 48 48h89.7c102.2 0 132.7 24.4 147.3 75 .7 2.8 5.2 2.8 6 0 14.7-50.6 45.2-75 147.3-75H528c26.5 0 48-21.5 48-48V94.6c0-26.4-21.3-47.9-47.7-48.1zM242 311.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5V289c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5V251zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm259.3 121.7c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5V228c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5v-22.8c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5V190z"},null,-1),pe=[me];function fe(c,_){return s(),n("svg",he,pe)}var I=L(_e,[["render",fe]]);const xe={class:"flex flex-col-reverse md:flex-row justify-between items-center mb-4"},ye={class:"bg-white rounded shadow overflow-x-auto hidden md:block"},ve={class:"w-full whitespace-nowrap"},be={class:"text-left font-semibold text-complement"},we=["textContent","colspan"],ke={key:0,class:"px-6 py4 border-t"},ge={class:""},Ce={key:1,class:"px-6 py4 border-t"},$e={class:"flex items-center justify-between"},He=["innerHTML"],Le=["innerHTML"],Me=["innerHTML"],ze=["textContent"],Ve={class:"border-t"},je={class:"action-icon"},Ie={class:"md:hidden"},Te={class:"flex justify-between items-center my-2 px-2"},Oe={class:"my-2 p-2 bg-gray-100 rounded space-y-2"},Be={key:0,class:"flex justify-end"},qe={class:"font-semibold text-complement text-xs flex items-center"},Ne={class:"block italic truncate"},Se={key:1,class:"flex justify-center items-center h-12"},Ae={key:1},De={class:"flex items-center justify-between"},Ee=["innerHTML"],Fe={class:"font-semibold text-complement text-xs flex items-center"},Pe={class:"block italic truncate"},Ke={class:"flex items-center justify-between"},Ue=T(" On : "),We=["textContent"],Xe={class:"flex items-center"},Ge=T(" Type : "),Je=["textContent"],Qe=t("template",null,null,-1),nt={__name:"CaseIndex",props:{cases:{type:Object,required:!0},configs:{type:Object,required:!0},filters:{type:Object,required:!0},routes:{type:Object,required:!0},can:{type:Object,required:!0}},setup(c){const _=c,k=N(()=>E(()=>import("./SearchAdmission.17518b5b.js"),["assets/SearchAdmission.17518b5b.js","assets/app.3be387f2.js","assets/app.ff361d0d.css","assets/ModalDialog.963dca7a.js","assets/ModalDialog.7d4de9ea.css","assets/FormInput.a6edf12a.js","assets/SpinnerButton.4cbba55d.js"])),v=H(null),b=S({hn:null,an:null}),h=A({search:_.filters.search,scope:_.filters.scope}),i=x=>{b.hn=x.hn,b.an=x.an,b.post(_.routes.store)},d=H(null);return D(()=>d.value.focus()),(x,p)=>(s(),n(a,null,[t("div",xe,[o(te,{scopes:c.configs.scopes,form:h,onSearchChanged:p[0]||(p[0]=e=>h.search=e),onScopeChanged:p[1]||(p[1]=e=>h.scope=e),ref_key:"searchInput",ref:d},null,8,["scopes","form"]),c.can.create?(s(),n("button",{key:0,class:"btn btn-accent w-full mb-4 md:w-auto md:px-4 md:mb-0",onClick:p[2]||(p[2]=e=>x.$refs.searchAdmission.open())}," Create New Case ")):C("",!0)]),t("div",ye,[t("table",ve,[t("tr",be,[(s(),n(a,null,y(["HN","Name","Status","On","Type","Order","MD"],e=>t("th",{class:"px-6 pt-6 pb-4",key:e,textContent:r(e),colspan:e==="MD"?2:1},null,8,we)),64))]),(s(!0),n(a,null,y(c.cases.data,(e,g)=>(s(),n("tr",{class:"focus-within:bg-primary-darker",key:g},[(s(),n(a,null,y(["hn","patient_name","case_status","date_note","dialysis_type","status","md"],f=>(s(),n(a,{key:f},[f==="date_note"?(s(),n("td",ke,[t("span",{class:$(["inline-flex h-6 w-6 mr-2 rounded-full items-center justify-center text-sm italic",{"text-complement bg-primary-darker":e.dialysis_at==="in","text-primary bg-complement":e.dialysis_at==="out"}])},r(e.dialysis_at),3),t("span",ge,r(e.date_note),1)])):f==="status"?(s(),n("td",Ce,[t("div",$e,[e.can.edit_order?(s(),n(a,{key:0},[t("span",{innerHTML:e[f]},null,8,He),o(u(m),{href:e.routes.edit_order,class:"action-icon"},{default:l(()=>[o(V,{class:"w-4 h-4"})]),_:2},1032,["href"])],64)):e.can.view_order?(s(),n(a,{key:1},[t("span",{innerHTML:e[f]},null,8,Le),o(u(m),{href:e.routes.view_order,class:"action-icon"},{default:l(()=>[o(I,{class:"w-4 h-4"})]),_:2},1032,["href"])],64)):e.can.create_order?(s(),w(u(m),{key:2,href:e.routes.create_order,class:"action-icon"},{default:l(()=>[o(j,{class:"w-4 h-4"})]),_:2},1032,["href"])):(s(),n("span",{key:3,innerHTML:e[f]},null,8,Me))])])):(s(),n("td",{key:2,class:"px-6 py4 border-t",textContent:r(e[f])},null,8,ze))],64))),64)),t("td",Ve,[o(u(m),{class:"px-4 py-2 flex items-center",href:e.routes.edit},{default:l(()=>[t("span",je,[o(M,{class:"w-4 h-4"})])]),_:2},1032,["href"])])]))),128))])]),t("div",Ie,[(s(!0),n(a,null,y(c.cases.data,(e,g)=>(s(),n("div",{class:"bg-white rounded shadow my-4 p-4",key:g},[t("div",Te,[t("div",null," HN: "+r(e.hn)+" "+r(e.patient_name),1),o(u(m),{href:e.routes.edit,class:"action-icon-mobile"},{default:l(()=>[o(M,{class:"w-4 h-4"})]),_:2},1032,["href"])]),t("div",Oe,[e.case_status!=="active"?(s(),n(a,{key:0},[e.md?(s(),n("div",Be,[t("p",qe,[o(z,{class:"h-3 w-3 mr-1"}),t("span",Ne,r(e.md),1)])])):C("",!0),t("p",{class:$(["text-center",{"pb-4":e.md}])},r(e.case_status),3)],64)):e.date_note?(s(),n(a,{key:2},[t("div",De,[t("span",{innerHTML:e.status},null,8,Ee),t("p",Fe,[o(z,{class:"h-3 w-3 mr-1"}),t("span",Pe,r(e.md),1)])]),t("div",Ke,[t("p",null,[Ue,t("span",{class:"text-complement font-semibold",textContent:r(e.date_note)},null,8,We),t("span",{class:$(["text-sm italic ml-1",{"text-accent":e.dialysis_at==="in","text-complement":e.dialysis_at==="out"}])},r(e.dialysis_at),3)]),t("p",Xe,[Ge,t("span",{class:"text-complement font-semibold",textContent:r(e.dialysis_type)},null,8,Je),e.can.edit_order?(s(),w(u(m),{key:0,href:e.routes.edit_order,class:"ml-2 p-2 rounded-full bg-primary-darker text-accent"},{default:l(()=>[o(V,{class:"w-4 h-4"})]),_:2},1032,["href"])):e.can.view_order?(s(),w(u(m),{key:1,href:e.routes.view_order,class:"ml-2 p-2 rounded-full bg-primary-darker text-accent"},{default:l(()=>[o(I,{class:"w-4 h-4"})]),_:2},1032,["href"])):C("",!0)])]),Qe],64)):(s(),n("div",Se,[e.can.create_order?(s(),w(u(m),{key:0,href:e.routes.create_order,class:"p-2 rounded-full bg-primary-darker text-accent"},{default:l(()=>[o(j,{class:"w-4 h-4"})]),_:2},1032,["href"])):(s(),n("span",Ae,r(e.case_status),1))]))])]))),128))]),o(K,{links:c.cases.links},null,8,["links"]),o(u(k),{ref_key:"searchAdmission",ref:v,onConfirmed:i,mode:"hn","service-endpoint":c.routes.serviceEndpoint},null,8,["service-endpoint"])],64))}};export{nt as default};