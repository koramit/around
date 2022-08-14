import{r as $,E as T,o as s,c as n,b as t,d as o,w as l,t as c,F as a,x,K as B,z as O,_ as H,U as N,h as S,v as A,g as q,j as C,u as d,V as D,l as L,L as _,s as b,e as j}from"./app.02e41e9a.js";import{p as E}from"./pickBy.9381ac6f.js";import{t as F}from"./throttle.987a746b.js";import{I as z}from"./IconDoubleRight.33e396da.js";import{I as P}from"./IconUserMd.346cd06d.js";import{_ as U}from"./PaginationNav.c3741f71.js";import"./debounce.403750ef.js";const K={class:"flex items-center w-full md:w-auto"},G=["value"],J={class:"flex justify-end form-input md:w-auto !border-l-0 !rounded-l-none"},Q={class:"flex items-center cursor-pointer select-none group"},W=t("div",null,"Scope : ",-1),X={class:"group-hover:text-accent-darker focus:text-accent-darker mr-1 whitespace-no-wrap"},Y={class:"text-complement group-hover:text-accent-darker focus:text-accent-darker"},Z=t("div",{class:"p-1 rounded-full bg-white hover:bg-primary transition-colors ease-in-out duration-200"},[t("svg",{class:"w-3 h-3 text-accent group-hover:text-accent-darker focus:text-accent-darker",viewBox:"0 0 320 512"},[t("path",{fill:"currentColor",d:"M143 256.3L7 120.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0L313 86.3c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.4 9.5-24.6 9.5-34 .1zm34 192l136-136c9.4-9.4 9.4-24.6 0-33.9l-22.6-22.6c-9.4-9.4-24.6-9.4-33.9 0L160 352.1l-96.4-96.4c-9.4-9.4-24.6-9.4-33.9 0L7 278.3c-9.4 9.4-9.4 24.6 0 33.9l136 136c9.4 9.5 24.6 9.5 34 .1z"})])],-1),R={class:"mt-2 py-2 shadow-xl bg-white text-complement cursor-pointer rounded text-sm"},ee=["onClick"],te={__name:"SearchIndex",props:{scopes:{type:Array,default:()=>[]},form:{type:Object,required:!0}},emits:["searchChanged","scopeChanged"],setup(r,{expose:u}){const k=r,y=$(null);return T(()=>k.form,F(function(h){let i=E(h);i=Object.keys(i).length?i:{remember:"forget"},O.Inertia.get(location.pathname,i,{preserveState:!0})},800),{deep:!0}),u({focus:()=>y.value.focus()}),(h,i)=>(s(),n("div",K,[t("input",{class:"form-input md:w-auto !border-r-0 !rounded-r-none",type:"text",name:"search",onInput:i[0]||(i[0]=m=>h.$emit("searchChanged",m.target.value)),value:r.form.search,placeholder:"search...",autocomplete:"off",ref_key:"searchInput",ref:y},null,40,G),t("div",J,[o(B,null,{default:l(()=>[t("div",Q,[W,t("div",X,[t("span",Y,c(r.form.scope),1)]),Z])]),dropdown:l(()=>[t("div",R,[(s(!0),n(a,null,x(r.scopes,(m,v)=>(s(),n("button",{class:"block w-full text-left font-semibold px-6 py-2 transition-colors duration-200 ease-out hover:bg-primary hover:text-accent-darker",key:v,onClick:p=>h.$emit("scopeChanged",m)},c(m),9,ee))),128))])]),_:1})])]))}},se={},ne={viewBox:"0 0 576 512"},oe=t("path",{fill:"currentColor",d:"M402.3 344.9l32-32c5-5 13.7-1.5 13.7 5.7V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h273.5c7.1 0 10.7 8.6 5.7 13.7l-32 32c-1.5 1.5-3.5 2.3-5.7 2.3H48v352h352V350.5c0-2.1.8-4.1 2.3-5.6zm156.6-201.8L296.3 405.7l-90.4 10c-26.2 2.9-48.5-19.2-45.6-45.6l10-90.4L432.9 17.1c22.9-22.9 59.9-22.9 82.7 0l43.2 43.2c22.9 22.9 22.9 60 .1 82.8zM460.1 174L402 115.9 216.2 301.8l-7.3 65.3 65.3-7.3L460.1 174zm64.8-79.7l-43.2-43.2c-4.1-4.1-10.8-4.1-14.8 0L436 82l58.1 58.1 30.9-30.9c4-4.2 4-10.8-.1-14.9z"},null,-1),re=[oe];function ce(r,u){return s(),n("svg",ne,re)}var M=H(se,[["render",ce]]);const ae={},le={viewBox:"0 0 448 512"},ie=t("path",{fill:"currentColor",d:"M336 292v24c0 6.6-5.4 12-12 12h-76v76c0 6.6-5.4 12-12 12h-24c-6.6 0-12-5.4-12-12v-76h-76c-6.6 0-12-5.4-12-12v-24c0-6.6 5.4-12 12-12h76v-76c0-6.6 5.4-12 12-12h24c6.6 0 12 5.4 12 12v76h76c6.6 0 12 5.4 12 12zm112-180v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"},null,-1),de=[ie];function ue(r,u){return s(),n("svg",le,de)}var V=H(ae,[["render",ue]]);const he={},_e={viewBox:"0 0 576 512"},me=t("path",{fill:"currentColor",d:"M528.3 46.5H388.5c-48.1 0-89.9 33.3-100.4 80.3-10.6-47-52.3-80.3-100.4-80.3H48c-26.5 0-48 21.5-48 48v245.8c0 26.5 21.5 48 48 48h89.7c102.2 0 132.7 24.4 147.3 75 .7 2.8 5.2 2.8 6 0 14.7-50.6 45.2-75 147.3-75H528c26.5 0 48-21.5 48-48V94.6c0-26.4-21.3-47.9-47.7-48.1zM242 311.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5V289c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5V251zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm259.3 121.7c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5V228c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5v-22.8c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5V190z"},null,-1),pe=[me];function fe(r,u){return s(),n("svg",_e,pe)}var I=H(he,[["render",fe]]);const ve={class:"flex flex-col-reverse md:flex-row justify-between items-center mb-4"},xe={class:"bg-white rounded shadow overflow-x-auto hidden md:block"},ye={class:"w-full whitespace-nowrap"},we={class:"text-left font-semibold text-complement"},be=["textContent","colspan"],ke={key:0,class:"px-6 py4 border-t"},ge={class:""},Ce={key:1,class:"px-6 py4 border-t"},$e={class:"flex items-center justify-between"},He=["innerHTML"],Le=["innerHTML"],ze=["innerHTML"],Me=["textContent"],Ve={class:"border-t"},Ie={class:"action-icon"},je={class:"md:hidden"},Te={class:"flex justify-between items-center my-2 px-2"},Be={class:"my-2 p-2 bg-gray-100 rounded space-y-2"},Oe={key:0,class:"flex justify-center items-center h-12"},Ne={class:"flex items-center justify-between"},Se=["innerHTML"],Ae={class:"font-semibold text-complement text-xs flex items-center"},qe={class:"block italic truncate"},De={class:"flex items-center justify-between"},Ee=j(" On : "),Fe=["textContent"],Pe={class:"flex items-center"},Ue=j(" Type : "),Ke=["textContent"],Ge=t("template",null,null,-1),et={__name:"CaseIndex",props:{cases:{type:Object,required:!0},filters:{type:Object,required:!0},routes:{type:Object,required:!0},can:{type:Object,required:!0}},setup(r){const u=r,k=N(()=>D(()=>import("./SearchAdmission.85339104.js"),["assets/SearchAdmission.85339104.js","assets/app.02e41e9a.js","assets/app.74cc65fe.css","assets/ModalDialog.46c8b581.js","assets/ModalDialog.db9c9a7c.css","assets/FormInput.bf9518e6.js","assets/SpinnerButton.acde9741.js"])),y=$(null),w=S({hn:null,an:null}),h=A({search:u.filters.search,scope:u.filters.scope}),i=v=>{w.hn=v.hn,w.an=v.an,w.post(u.routes.store)},m=$(null);return q(()=>m.value.focus()),(v,p)=>(s(),n(a,null,[t("div",ve,[o(te,{scopes:["active","discharged","all"],form:h,onSearchChanged:p[0]||(p[0]=e=>h.search=e),onScopeChanged:p[1]||(p[1]=e=>h.scope=e),ref_key:"searchInput",ref:m},null,8,["form"]),r.can.create?(s(),n("button",{key:0,class:"btn btn-accent w-full mb-4 md:w-auto md:px-4 md:mb-0",onClick:p[2]||(p[2]=e=>v.$refs.searchAdmission.open())}," Create New Case ")):C("",!0)]),t("div",xe,[t("table",ye,[t("tr",we,[(s(),n(a,null,x(["HN","Name","On","Type","Status","MD"],e=>t("th",{class:"px-6 pt-6 pb-4",key:e,textContent:c(e),colspan:e==="MD"?2:1},null,8,be)),64))]),(s(!0),n(a,null,x(r.cases.data,(e,g)=>(s(),n("tr",{class:"focus-within:bg-primary-darker",key:g},[(s(),n(a,null,x(["hn","patient_name","date_note","dialysis_type","status","md"],f=>(s(),n(a,{key:f},[f==="date_note"?(s(),n("td",ke,[t("span",{class:L(["inline-flex h-6 w-6 mr-2 rounded-full items-center justify-center text-sm italic",{"text-complement bg-primary-darker":e.dialysis_at==="in","text-primary bg-complement":e.dialysis_at==="out"}])},c(e.dialysis_at),3),t("span",ge,c(e.date_note),1)])):f==="status"?(s(),n("td",Ce,[t("div",$e,[e.can.edit_order?(s(),n(a,{key:0},[t("span",{innerHTML:e[f]},null,8,He),o(d(_),{href:e.routes.edit_order,class:"action-icon"},{default:l(()=>[o(M,{class:"w-4 h-4"})]),_:2},1032,["href"])],64)):e.can.view_order?(s(),n(a,{key:1},[t("span",{innerHTML:e[f]},null,8,Le),o(d(_),{href:e.routes.view_order,class:"action-icon"},{default:l(()=>[o(I,{class:"w-4 h-4"})]),_:2},1032,["href"])],64)):e.can.create_order?(s(),b(d(_),{key:2,href:e.routes.create_order,class:"action-icon"},{default:l(()=>[o(V,{class:"w-4 h-4"})]),_:2},1032,["href"])):(s(),n("span",{key:3,innerHTML:e[f]},null,8,ze))])])):(s(),n("td",{key:2,class:"px-6 py4 border-t",textContent:c(e[f])},null,8,Me))],64))),64)),t("td",Ve,[o(d(_),{class:"px-4 py-2 flex items-center",href:e.routes.edit},{default:l(()=>[t("span",Ie,[o(z,{class:"w-4 h-4"})])]),_:2},1032,["href"])])]))),128))])]),t("div",je,[(s(!0),n(a,null,x(r.cases.data,(e,g)=>(s(),n("div",{class:"bg-white rounded shadow my-4 p-4",key:g},[t("div",Te,[t("div",null," HN: "+c(e.hn)+" "+c(e.patient_name),1),o(d(_),{href:e.routes.edit,class:"action-icon-mobile"},{default:l(()=>[o(z,{class:"w-4 h-4"})]),_:2},1032,["href"])]),t("div",Be,[e.md?(s(),n(a,{key:1},[t("div",Ne,[t("span",{innerHTML:e.status},null,8,Se),t("p",Ae,[o(P,{class:"h-3 w-3 mr-1"}),t("span",qe,c(e.md),1)])]),t("div",De,[t("p",null,[Ee,t("span",{class:"text-complement font-semibold",textContent:c(e.date_note)},null,8,Fe),t("span",{class:L(["text-sm italic ml-1",{"text-accent":e.dialysis_at==="in","text-complement":e.dialysis_at==="out"}])},c(e.dialysis_at),3)]),t("p",Pe,[Ue,t("span",{class:"text-complement font-semibold",textContent:c(e.dialysis_type)},null,8,Ke),e.can.edit_order?(s(),b(d(_),{key:0,href:e.routes.edit_order,class:"ml-2 p-2 rounded-full bg-primary-darker text-accent"},{default:l(()=>[o(M,{class:"w-4 h-4"})]),_:2},1032,["href"])):e.can.view_order?(s(),b(d(_),{key:1,href:e.routes.view_order,class:"ml-2 p-2 rounded-full bg-primary-darker text-accent"},{default:l(()=>[o(I,{class:"w-4 h-4"})]),_:2},1032,["href"])):C("",!0)])]),Ge],64)):(s(),n("div",Oe,[e.can.create_order?(s(),b(d(_),{key:0,href:e.routes.create_order,class:"p-2 rounded-full bg-primary-darker text-accent"},{default:l(()=>[o(V,{class:"w-4 h-4"})]),_:2},1032,["href"])):C("",!0)]))])]))),128))]),o(U,{links:r.cases.links},null,8,["links"]),o(d(k),{ref_key:"searchAdmission",ref:y,onConfirmed:i,mode:"hn","service-endpoint":r.routes.serviceEndpoint},null,8,["service-endpoint"])],64))}};export{et as default};