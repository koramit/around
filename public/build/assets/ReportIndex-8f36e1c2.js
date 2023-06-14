import{l as R,r as k,j as S,b as a,k as j,e as s,f as _,d as o,q as T,c as r,h as V,D as N,n as O,o as H,F as c,m as v,u as L,T as F,t as m,E as B,g as U}from"./app-4c4070f2.js";import{u as E}from"./useConfirmForm-66b42655.js";import{_ as I,I as P,a as M}from"./IconDoubleDown-1b762d99.js";import K from"./ConfirmFormComposable-7e45d6db.js";import{M as z}from"./ModalDialog-a959e4f2.js";import{_ as p}from"./FormInput-084b806e.js";import{_ as G}from"./FormDatetime-b6c47c13.js";import{_ as J}from"./FormRadio-c474b0ab.js";import{_ as A}from"./FormCheckbox-dc0d4696.js";import{_ as Q}from"./PaginationNav-c7f75fb4.js";import{_ as W}from"./SearchIndex-c48b0701.js";import{_ as D}from"./NoteStatusBadge-a7e73777.js";import"./SpinnerButton-91e436ba.js";import"./pickBy-d62da4cb.js";import"./throttle-e5d15860.js";const X=o("div",{class:"font-semibold text-complement"}," HLA typing for KT Report ",-1),Y={class:"py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent-darker"},Z=o("label",{class:"form-label"},"patient type :",-1),ee={key:0,class:"space-y-2"},te={key:0,class:"space-y-2"},ne={key:1,class:"space-y-2 md:space-y-0 md:grid grid-cols-2 gap-4"},oe=o("label",{class:"form-label"},"Cause of investigation",-1),se={class:"sm:grid-cols-2 gap-x-2"},le={class:"flex justify-end items-center"},ae=["disabled"],re=o("label",{class:"px-4"},"Confirm",-1),ie=[re],de={__name:"CreateForm",props:{serviceEndpoint:{type:String,required:!0}},emits:["confirmed"],setup(d,{expose:f,emit:y}){const x=d,t=R({patient_type:null,hn:null,patient_name:null,patient_error:null,date_serum:null,donor_hn:null,donor_name:null,donor_error:null,request_hla:!1,request_cxm:!1,request_addition_tissue:!1}),h=k(null),i=k(null),u=l=>{let n;l==="recipient"?(n=t.hn,t.patient_name=null,t.patient_error=null):(n=t.donor_hn,t.donor_name=null,t.donor_error=null),window.axios.post(x.serviceEndpoint,{hn:n}).then(e=>{e.data.found?l==="recipient"?t.patient_name=e.data.name:t.donor_name=e.data.name:l==="recipient"?t.patient_error=e.data.message:t.donor_error=e.data.message}).catch(e=>console.log(e))},q=()=>{h.value.open()},g=()=>{O(()=>i.value.focus())},w=S(()=>t.patient_type?t.patient_type==="Patient"?!t.patient_name||!t.date_serum||!t.request_cxm&&!t.request_hla&&!t.request_addition_tissue:!t.patient_name||!t.date_serum||!t.request_cxm&&!t.request_hla&&!t.request_addition_tissue||!t.donor_name:!0),$=()=>{h.value.close(),y("confirmed",{...t})};return f({open:q}),(l,n)=>(a(),j(N,{to:"body"},[s(z,{ref_key:"modal",ref:h,"width-mode":"form-cols-1",onClosed:n[14]||(n[14]=e=>Object.keys(t).map(b=>t[b]=null))},{header:_(()=>[X]),body:_(()=>[o("div",Y,[Z,s(J,{name:"patient_type",class:"md:grid grid-cols-2 gap-4",modelValue:t.patient_type,"onUpdate:modelValue":n[0]||(n[0]=e=>t.patient_type=e),options:["Patient","Recipient with LD"],onAutosave:g},null,8,["modelValue"]),s(T,{name:"slide-fade"},{default:_(()=>[t.patient_type?(a(),r("div",ee,[t.patient_type==="Patient"?(a(),r("div",te,[s(p,{label:"patient hn",name:"hn",modelValue:t.hn,"onUpdate:modelValue":n[1]||(n[1]=e=>t.hn=e),type:"tel",error:t.patient_error,ref_key:"hnInput",ref:i,onAutosave:n[2]||(n[2]=e=>u("recipient"))},null,8,["modelValue","error"]),s(p,{label:"patient name",name:"patient_name",modelValue:t.patient_name,"onUpdate:modelValue":n[3]||(n[3]=e=>t.patient_name=e),readonly:""},null,8,["modelValue"])])):t.patient_type==="Recipient with LD"?(a(),r("div",ne,[s(p,{label:"recipient hn",name:"hn",modelValue:t.hn,"onUpdate:modelValue":n[4]||(n[4]=e=>t.hn=e),type:"tel",error:t.patient_error,ref_key:"hnInput",ref:i,onAutosave:n[5]||(n[5]=e=>u("recipient"))},null,8,["modelValue","error"]),s(p,{label:"recipient name",name:"patient_name",modelValue:t.patient_name,"onUpdate:modelValue":n[6]||(n[6]=e=>t.patient_name=e),readonly:""},null,8,["modelValue"]),s(p,{label:"donor hn",name:"donor_hn",modelValue:t.donor_hn,"onUpdate:modelValue":n[7]||(n[7]=e=>t.donor_hn=e),type:"tel",error:t.donor_error,onAutosave:n[8]||(n[8]=e=>u("donor"))},null,8,["modelValue","error"]),s(p,{label:"donor name",name:"donor_name",modelValue:t.donor_name,"onUpdate:modelValue":n[9]||(n[9]=e=>t.donor_name=e),readonly:""},null,8,["modelValue"])])):V("",!0),s(G,{label:"collection date",name:"date_serum",modelValue:t.date_serum,"onUpdate:modelValue":n[10]||(n[10]=e=>t.date_serum=e)},null,8,["modelValue"]),o("div",null,[oe,o("div",se,[s(A,{label:"HLA typing",name:"request_hla",modelValue:t.request_hla,"onUpdate:modelValue":n[11]||(n[11]=e=>t.request_hla=e)},null,8,["modelValue"]),s(A,{label:"Addition Tissue Typing",name:"request_addition_tissue",modelValue:t.request_addition_tissue,"onUpdate:modelValue":n[12]||(n[12]=e=>t.request_addition_tissue=e)},null,8,["modelValue"]),s(A,{label:"Crossmatch",name:"request_cxm",modelValue:t.request_cxm,"onUpdate:modelValue":n[13]||(n[13]=e=>t.request_cxm=e)},null,8,["modelValue"])])])])):V("",!0)]),_:1})])]),footer:_(()=>[o("div",le,[o("button",{class:"btn btn-accent",onClick:$,disabled:w.value},ie,8,ae)])]),_:1},512)]))}},ue={class:"flex flex-col-reverse md:flex-row justify-between items-center mb-4"},me={class:"bg-white rounded shadow overflow-x-auto hidden md:block"},ce={class:"w-full whitespace-nowrap"},pe={class:"text-left font-semibold text-complement"},_e=["textContent","colspan"],fe={key:0,class:"px-6 py-4 border-t"},ye=["textContent"],he={class:"border-t"},be={class:"flex justify-between items-center"},xe={class:"md:hidden"},ve={class:"flex justify-between items-center my-2 px-2"},ke={class:"bg-primary-darker p-2 rounded-full"},Ve={class:"my-2 p-2 bg-gray-100 rounded space-y-2"},qe={class:"flex items-center justify-between"},ge={class:"font-semibold text-complement text-xs flex items-center"},we={class:"block py-2 px-1 italic truncate"},$e={class:"flex items-center justify-between text-xs"},Ce=["textContent"],Ae=["textContent"],Me={__name:"ReportIndex",props:{reports:{type:Object,required:!0},configs:{type:Object,required:!0},filters:{type:Object,required:!0},routes:{type:Object,required:!0},can:{type:Object,required:!0}},setup(d){const f=d,y=R({search:f.filters.search,scope:f.filters.scope}),x=k(null),t=k(null),h=l=>{F({patient_type:l.patient_type,hn:l.hn,date_serum:l.date_serum,request_hla:l.request_hla??!1,request_cxm:l.request_cxm??!1,request_addition_tissue:l.request_addition_tissue??!1,donor_hn:l.donor_hn??null}).post(f.routes.reportsStore)};let i=null;const u=l=>{switch(i={...l},i.name){case"destroy-report":case"cancel-report":w(i.config);break}},q=l=>{switch(i.name){case"destroy-report":case"cancel-report":F({reason:l}).delete(i.route,{onFinish:()=>i=null});break}},{confirmForm:g,openConfirmForm:w,confirmed:$}=E();return H(()=>x.value.focus()),(l,n)=>(a(),r(c,null,[o("div",ue,[s(W,{scopes:d.configs.scopes,form:y,onSearchChanged:n[0]||(n[0]=e=>y.search=e),onScopeChanged:n[1]||(n[1]=e=>y.scope=e),ref_key:"searchInput",ref:x},null,8,["scopes","form"]),d.can.create?(a(),r("button",{key:0,class:"btn btn-accent w-full mb-4 md:w-auto md:px-4 md:mb-0",onClick:n[2]||(n[2]=e=>l.$refs.createForm.open())}," New Report ")):V("",!0)]),o("div",me,[o("table",ce,[o("tr",pe,[(a(),r(c,null,v(["HN","Patient","Request","On","Status","Author"],e=>o("th",{class:"px-6 pt-6 pb-4",key:e,textContent:m(e),colspan:e==="Author"?2:1},null,8,_e)),64))]),(a(!0),r(c,null,v(d.reports.data,(e,b)=>(a(),r("tr",{class:"focus-within:bg-primary-darker",key:b},[(a(),r(c,null,v(["hn","patient_name","request","date_serum","status","author"],C=>(a(),r(c,{key:C},[C==="status"?(a(),r("td",fe,[s(D,{status:e.status},null,8,["status"])])):(a(),r("td",{key:1,class:"px-6 py-4 border-t",textContent:m(e[C])},null,8,ye))],64))),64)),o("td",he,[o("div",be,[s(I,{actions:e.actions,onActionClicked:u},null,8,["actions"])])])]))),128))])]),o("div",xe,[(a(!0),r(c,null,v(d.reports.data,(e,b)=>(a(),r("div",{class:"bg-white rounded shadow my-4 p-4",key:b},[o("div",ve,[o("div",null," HN: "+m(e.hn)+" "+m(e.patient_name),1),e.actions.length>1?(a(),j(B,{key:0},{default:_(()=>[o("div",ke,[s(P,{class:"w-4 h-4 text-accent"})])]),dropdown:_(()=>[s(M,{actions:e.actions,onActionClicked:u},null,8,["actions"])]),_:2},1024)):e.actions.length===1?(a(),j(I,{key:1,actions:e.actions,onActionClicked:u},null,8,["actions"])):V("",!0)]),o("div",Ve,[o("div",qe,[s(D,{status:e.status},null,8,["status"]),o("p",ge,[o("span",we,m(e.author),1)])]),o("div",$e,[o("p",null,[U(" Serum : "),o("span",{class:"text-complement font-semibold",textContent:m(e.date_serum)},null,8,Ce)]),o("p",null,[U(" Request: "),o("span",{class:"text-complement font-semibold px-1",textContent:m(e.request)},null,8,Ae)])])])]))),128))]),s(Q,{links:d.reports.links},null,8,["links"]),s(de,{"service-endpoint":d.routes.patientsShow,onConfirmed:h,ref_key:"createForm",ref:t},null,8,["service-endpoint"]),s(K,{ref_key:"confirmForm",ref:g,onConfirmed:n[3]||(n[3]=e=>L($)(e,q))},null,512)],64))}};export{Me as default};
