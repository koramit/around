import{_ as B}from"./FormDatetime.70b9b4e7.js";import D from"./DialysisSlot.17c0ebcc.js";import{I as z,_ as R}from"./WardSlot.5dfc30cd.js";import{r as w,o as n,c as i,j as g,b as s,t as y,l as S,u as l,d as o,w as b,F as x,x as $,T as I,h as L,v as T,E as V,g as P,q,e as E,s as N,S as W,I as U,ae as G,z as H}from"./app.02e41e9a.js";import{t as K}from"./throttle.987a746b.js";import{_ as C}from"./FormAutocomplete.aaeb40da.js";import{_ as M}from"./FormSelect.86a28591.js";import{_ as Q}from"./FormRadio.b60df8ef.js";import{_ as j}from"./SpinnerButton.acde9741.js";import{_ as O}from"./FormCheckbox.3de4216a.js";import{F as J,_ as X}from"./FallbackSpinner.7282f6b6.js";import{I as F}from"./IconUserMd.346cd06d.js";import{p as Y}from"./pickBy.9381ac6f.js";import"./debounce.403750ef.js";const Z={class:"relative"},ee={class:"w-full"},te=["for"],se={class:"relative"},le=["id","name","value"],ae=s("div",{class:"pointer-events-none absolute inset-y-0 right-0 flex items-center px-2"},[s("svg",{xmlns:"http://www.w3.org/2000/svg",class:"h-4 w-4",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[s("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"})])],-1),oe={key:1,class:"text-red-700 mt-2 text-sm"},ne=["onClick"],re={__name:"FormAutocompleteKeyValue",props:{keyModel:{type:[String,null],default:null},valueModel:{type:[String,null],default:null},label:{type:String,default:""},endpoint:{type:String,default:""},params:{type:String,default:""},name:{type:String,required:!0},error:{type:String,default:""},lengthToStart:{type:[Number,String],default:3}},emits:["update:keyModel","update:valueModel","autosave"],setup(a,{emit:d}){const e=a,_=w([]),t=w(null),c=w(!1),f=K(function(){if(d("update:valueModel",t.value.value),d("update:keyModel",null),t.value.value.length<e.lengthToStart){c.value&&(c.value=!1),t.value.value||d("autosave");return}window.axios.get(e.endpoint+"?search="+t.value.value+e.params).then(v=>{_.value=v.data.length?v.data:[{key:"",value:"No match found"}],c.value=!0}).catch(v=>{console.log(v)})},300),m=v=>{t.value.value=v.value,c.value=!1,d("update:valueModel",v.value),d("update:keyModel",v.key),d("autosave")};return(v,k)=>(n(),i("div",Z,[c.value?(n(),i("div",{key:0,class:"fixed inset-0 z-10",onClick:k[0]||(k[0]=h=>c.value=!1)})):g("",!0),s("div",ee,[a.label?(n(),i("label",{key:0,class:"form-label",for:a.name},y(a.label)+" : ",9,te)):g("",!0),s("div",se,[s("input",{type:"text",class:S(["form-input",{"border-red-400 text-red-400":a.error}]),onInput:k[1]||(k[1]=(...h)=>l(f)&&l(f)(...h)),id:a.name,name:a.name,ref_key:"input",ref:t,value:a.valueModel},null,42,le),ae]),a.error?(n(),i("div",oe,y(a.error),1)):g("",!0)]),o(I,{name:"fade-appear"},{default:b(()=>[c.value?(n(),i("div",{key:0,class:S(["absolute mt-1 bg-white rounded border-2 border-yellow-200 shadow-xl w-full max-h-44 py-2 overflow-y-scroll z-20 origin-top",{"scale-100 opacity-100":c.value}])},[(n(!0),i(x,null,$(_.value,h=>(n(),i("button",{class:"block w-full py-1 px-2 lg:px-3 hover:bg-primary hover:text-accent text-left",key:h.key,onClick:p=>m(h)},y(h.value),9,ne))),128))],2)):g("",!0)]),_:1})]))}},ie={class:"flex flex-col-reverse md:flex-row md:space-x-4"},de={class:"space-y-2 md:w-1/2"},ce=s("label",{class:"form-label"},"patient type",-1),ue=E(" Check available dates "),me={key:0,class:"space-y-2"},fe={class:"md:w-1/2"},_e={__name:"SlotReservationForm",props:{configs:{type:Object,required:!0}},setup(a){const d=a,e=L({case_key:null,attending_staff:null,dialysis_type:null,patient_type:null,dialysis_at:null,covid_case:!1,case_label:null,date_note:null}),_=T({route_lab:d.configs.covid.route_lab,route_vaccine:d.configs.covid.route_vaccine,hn:null,cid:null});V(()=>e.case_key,p=>{if(!p){_.hn=null;return}let r=p.split("|");_.cid=r[2],_.hn=r[1],console.log(_)}),P(()=>{d.configs.case&&(e.case_key=d.configs.case.value,e.case_label=d.configs.case.label)}),V([()=>e.dialysis_type,()=>e.dialysis_at,()=>e.covid_case],()=>{c.value.length&&(c.value=[],e.date_note=null)}),V(()=>e.date_note,p=>{if(!p)return;let r=c.value.findIndex(u=>u.value===p);r!==-1&&(c.value[r].error!==void 0?e.errors.date_note=c.value[r].error:e.errors.date_note=null)});const t=()=>e.transform(p=>({...p,case_record_hashed_key:p.case_key.split("|")[0]})).post(d.configs.routes.orders_store,{preserveState:!0,onFinish:()=>{e.processing=!1}}),c=w([]),f=w(!1),m=q(()=>!e.dialysis_type||!e.dialysis_at),v=()=>{f.value=!0,window.axios.post(d.configs.routes.slot_available_dates,e.data()).then(p=>c.value=[...p.data]).catch(p=>console.log(p)).finally(()=>f.value=!1)},k=w(!1);V(()=>e.covid_case,p=>{k.value=p,p?e.dialysis_at=d.configs.covid_ward:e.dialysis_at=null});const h=q(()=>e.covid_case?d.configs.covid_dialysis:e.dialysis_at&&e.dialysis_at.search("Hemodialysis")!==-1?d.configs.in_unit_dialysis_types:d.configs.out_unit_dialysis_types);return(p,r)=>(n(),i("div",ie,[s("div",de,[o(re,{name:"case",label:"case","key-model":l(e).case_key,"onUpdate:key-model":r[0]||(r[0]=u=>l(e).case_key=u),"value-model":l(e).case_label,"onUpdate:value-model":r[1]||(r[1]=u=>l(e).case_label=u),error:l(e).errors.case_key,endpoint:a.configs.routes.idle_cases},null,8,["key-model","value-model","error","endpoint"]),o(O,{class:"border border-dashed border-l-0 border-r-0 border-accent py-2",label:"covid-19 infected",toggler:!0,name:"covid_case",modelValue:l(e).covid_case,"onUpdate:modelValue":r[2]||(r[2]=u=>l(e).covid_case=u)},null,8,["modelValue"]),o(C,{label:"dialysis at",name:"dialysis_at",modelValue:l(e).dialysis_at,"onUpdate:modelValue":r[3]||(r[3]=u=>l(e).dialysis_at=u),endpoint:a.configs.routes.resources_api_wards,error:l(e).errors.dialysis_at,"length-to-start":1,disabled:k.value},null,8,["modelValue","endpoint","error","disabled"]),o(M,{label:"dialysis type",name:"dialysis_type",modelValue:l(e).dialysis_type,"onUpdate:modelValue":r[4]||(r[4]=u=>l(e).dialysis_type=u),options:l(h),disabled:!l(e).dialysis_at},null,8,["modelValue","options","disabled"]),s("div",null,[ce,o(Q,{class:"grid grid-cols-2 gap-x-2",name:"patient_type",modelValue:l(e).patient_type,"onUpdate:modelValue":r[5]||(r[5]=u=>l(e).patient_type=u),options:a.configs.patient_types,ref:"patientTypeInput"},null,8,["modelValue","options"])]),o(C,{label:"attending",name:"attending_staff",modelValue:l(e).attending_staff,"onUpdate:modelValue":r[6]||(r[6]=u=>l(e).attending_staff=u),endpoint:a.configs.routes.resources_api_staffs,params:a.configs.routes.staffs_scope_params,error:l(e).errors.attending_staff,"length-to-start":1},null,8,["modelValue","endpoint","params","error"]),o(j,{class:"btn btn-complement w-full",spin:f.value,disabled:l(m),onClick:v},{default:b(()=>[ue]),_:1},8,["spin","disabled"]),o(I,{name:"slide-fade"},{default:b(()=>[c.value.length?(n(),i("div",me,[o(M,{label:"required date",name:"date_note",modelValue:l(e).date_note,"onUpdate:modelValue":r[7]||(r[7]=u=>l(e).date_note=u),options:c.value,error:l(e).errors.date_note},null,8,["modelValue","options","error"]),o(j,{class:"btn btn-accent w-full",spin:l(e).processing,disabled:Object.keys(l(e).data()).reduce((u,A)=>u||l(e)[A]===null,!1),onClick:t},{default:b(()=>{var u;return[E(y(((u=l(e).date_note)!=null?u:"").includes("Approval")?"REQUEST ":"")+" RESERVE ",1)]}),_:1},8,["spin","disabled"])])):g("",!0)]),_:1})]),s("div",fe,[o(I,{mode:"out-in"},{default:b(()=>[_.hn?(n(),N(W,{key:0},{fallback:b(()=>[o(J)]),default:b(()=>[o(X,{configs:_},null,8,["configs"])]),_:1})):g("",!0)]),_:1})])]))}},pe={key:0},ye=s("p",{class:"mt-1 italic text-xs text-accent"}," \u0E4F Not in any particular order ",-1),ve={key:0,class:"mt-2 lg:mt-0 mb-2 md:mb-4 grid grid-cols-1 gap-2"},be={class:"mt-2 md:mt-4 form-label"},ge={class:"w=1/4"},he={class:"w-3/4 mt-1 mt-0 space-x-2 flex items-center"},xe={class:"block py-1 italic truncate underline"},ke={class:"font-semibold text-xs flex items-center"},we={class:"block py-1 italic truncate"},$e={class:"font-semibold text-xs flex items-center"},Ve={class:"block py-1 italic truncate"},Se={key:1,class:"mt-1 italic text-xs text-accent"},Ie={__name:"CovidSlot",props:{slots:{type:Object,required:!0}},setup(a){return(d,e)=>a.slots.in.length||a.slots.out.length?(n(),i("div",pe,[ye,(n(),i(x,null,$(["in","out"],_=>s("div",{key:_},[o(G,{name:"flip-list",tag:"div"},{default:b(()=>[a.slots[_].length?(n(),i("div",ve,[s("label",be,y(_==="in"?"Acute Dialysis Unit":"Ward"),1),(n(!0),i(x,null,$(a.slots[_],(t,c)=>(n(),i("div",{class:S(["w-full p-2 md:p-4 rounded shadow",{"bg-green-400 p-4 h-8":!t.type,"flex justify-between items-center":t.type,"text-complement-darker bg-amber-400":t.status!==void 0&&t.status==="scheduling","text-primary bg-complement":t.status!==void 0&&(t.status==="started"||t.status==="finished"),"text-primary bg-red-400":t.status!==void 0&&t.status!=="scheduling"&&t.status!=="started"&&t.status!=="finished"}]),key:c},[t.type?(n(),i(x,{key:0},[s("div",ge,[o(l(U),{href:t.order_route},{default:b(()=>[s("span",{class:S(["p-1 md:p-2 rounded-full text-xs font-semibold underline",{"bg-primary text-accent":t.status!=="submitted","bg-indigo-400 text-white":t.status==="submitted"}])},y(t.type),3)]),_:2},1032,["href"])]),s("div",he,[o(l(U),{class:"font-semibold text-xs flex items-center",href:t.case_record_route},{default:b(()=>[o(z,{class:"h-3 w-3 mr-1 text-white"}),s("span",xe,y(t.patient_name),1)]),_:2},1032,["href"]),s("p",ke,[o(F,{class:"h-3 w-3 mr-1 text-white"}),s("span",we,y(t.author),1)]),s("p",$e,[o(F,{class:"h-3 w-3 mr-1 text-white"}),s("span",Ve,y(t.attending),1)])])],64)):g("",!0)],2))),128))])):g("",!0)]),_:2},1024)])),64))])):(n(),i("p",Se," \u0E4F No cases "))}},qe={class:"md:flex items-end md:space-x-4"},Ue=["href"],Ce=s("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"}," Acute dialysis unit ",-1),Me={class:"grid grid-flow-col grid-rows-1 gap-4 xl:gap-8 overflow-x-scroll pb-2 md:pb-4 border-b-2 border-dashed border-complement"},je={class:"font-medium text-complement italic"},Fe=s("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"}," Ward ",-1),Te={class:"grid grid-flow-col grid-rows-1 gap-4 xl:gap-8 overflow-x-scroll pb-2 md:pb-4 border-b-2 border-dashed border-complement"},Ee={class:"font-medium text-complement italic"},Ne=s("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"}," COVID cases ",-1),Oe={class:"grid grid-flow-col grid-rows-1 gap-4 xl:gap-8 overflow-x-scroll"},Ae={class:"font-medium text-complement italic"},Ze={__name:"ScheduleIndex",props:{query:{type:Object,required:!0},slots:{type:Array,required:!0},configs:{type:Object,required:!0}},setup(a){var _,t;const d=a,e=T({ref_date:(_=d.query.ref_date)!=null?_:null,full_week:((t=d.query.full_week)!=null?t:!1)==="on"});return V(()=>e,c=>{let f=Y(c);f=Object.keys(f).length?f:{remember:"forget"},f.full_week!==void 0&&(f.full_week="on"),H.Inertia.get(location.pathname,f,{preserveState:!0,preserveScroll:!0})},{deep:!0}),(c,f)=>(n(),i(x,null,[a.configs.can.create_order?(n(),N(_e,{key:0,class:"border-b-2 border-dashed pb-2 mb-2 md:pb-4 md:mb-4",configs:a.configs},null,8,["configs"])):g("",!0),s("div",qe,[o(B,{label:"reference date",name:"ref_date",modelValue:e.ref_date,"onUpdate:modelValue":f[0]||(f[0]=m=>e.ref_date=m)},null,8,["modelValue"]),s("a",{class:"block mt-4 md:mt-0 w-full btn btn-complement h-10 text-center",href:a.configs.routes.orders_export}," Export excel ",8,Ue)]),o(O,{class:"mt-6 md:mt-12 xl:mt-24",toggler:!0,modelValue:e.full_week,"onUpdate:modelValue":f[1]||(f[1]=m=>e.full_week=m),label:"Full week"},null,8,["modelValue"]),Ce,s("div",Me,[(n(!0),i(x,null,$(a.slots,m=>(n(),i("div",{class:"w-[21rem] sm:w-[22-rem] md:w-[24rem]",key:m.date_note},[s("label",je,y(m.date_label),1),o(D,{slots:m.hd_unit},null,8,["slots"])]))),128))]),Fe,s("div",Te,[(n(!0),i(x,null,$(a.slots,m=>(n(),i("div",{class:"w-[21rem] sm:w-[22-rem] md:w-[24rem]",key:m.date_note},[s("label",Ee,y(m.date_label),1),o(R,{slots:m.ward},null,8,["slots"])]))),128))]),Ne,s("div",Oe,[(n(!0),i(x,null,$(a.slots,m=>(n(),i("div",{class:"w-[21rem] sm:w-[22-rem] md:w-[24rem]",key:m.date_note},[s("label",Ae,y(m.date_label),1),o(Ie,{slots:m.covid_cases},null,8,["slots"])]))),128))])],64))}};export{Ze as default};