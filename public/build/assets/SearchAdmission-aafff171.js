import{r,l as E,j as N,b as d,k as j,e as f,f as u,d as n,t as s,w as B,g as v,c as o,s as L,F as y,m as w,D,n as F}from"./app-4c4070f2.js";import{M as I}from"./ModalDialog-a959e4f2.js";import{_ as K}from"./FormInput-084b806e.js";import{_ as M}from"./SpinnerButton-91e436ba.js";const O={class:"font-semibold text-complement"},T={class:"py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent-darker"},R=n("hr",{class:"my-4 md:my-6"},null,-1),$={class:"form-label block"},q={class:"bg-gray-100 text-gray-100 whitespace-nowrap"},z={key:1,class:"bg-white rounded shadow p-2 lg:p-4 text-sm"},H={class:"text-complement uppercase font-semibold"},P={class:"flex justify-end items-center"},U=["disabled"],X={__name:"SearchAdmission",props:{heading:{type:String,default:"Search Admission"},confirmLabel:{type:String,default:"CONFIRM"},mode:{type:String,default:"an"},serviceEndpoint:{type:String,required:!0}},emits:["confirmed"],setup(c,{expose:k,emit:x}){const _=c,h=r(null),p=r(null),l=r(""),i=r(""),m=r(!1),a=E({an:"",hn:"",name:"",gender:"",age:"",ward_admit:"",admitted_at:"",discharged_at:""}),S=N(()=>a.admitted_at?a.discharged_at?"latest admission":"active admission":a.hn?"patient data":null),b=()=>{m.value=!0,i.value="",a.hn="",window.axios.post(_.serviceEndpoint,{key:l.value}).then(e=>{if(!e.data.hn){i.value="Patient not found";return}(!e.data.found||e.data.discharged_at)&&(i.value="No active admission"),_.mode==="hn"&&(a.location=e.data.location),a.hn=e.data.hn,a.an=e.data.an,a.name=e.data.name,a.gender=e.data.gender,a.age=e.data.age,a.ward_admit=e.data.ward_admit,a.admitted_at=e.data.admitted_at,a.discharged_at=e.data.discharged_at}).catch(e=>{console.log(e)}).finally(()=>m.value=!1)},C=()=>{l.value="",i.value="",a.hn=""},A=()=>{h.value.open(),F(()=>p.value.focus())},V=()=>{h.value.close(),x("confirmed",{hn:a.hn,an:a.an})};return k({open:A}),(e,g)=>(d(),j(D,{to:"body"},[f(I,{ref_key:"modal",ref:h,"width-mode":"form-cols-1",onClosed:C},{header:u(()=>[n("div",O,s(c.heading),1)]),body:u(()=>[n("div",T,[f(K,{name:"an",label:c.mode,modelValue:l.value,"onUpdate:modelValue":g[0]||(g[0]=t=>l.value=t),pattern:"\\d*",type:"number",ref_key:"anInput",ref:p,error:i.value,onKeydown:B(b,["enter"])},null,8,["label","modelValue","error","onKeydown"]),f(M,{spin:m.value,class:"btn-complement w-full mt-2",onClick:b,disabled:!l.value.length},{default:u(()=>[v(" SEARCH ")]),_:1},8,["spin","disabled"]),R,n("span",$,s(S.value),1),a.hn?(d(),o("div",z,[(d(!0),o(y,null,w([...Object.keys(a)].filter(t=>a[t]),t=>(d(),o("p",{class:"mt-1 whitespace-nowrap",key:t},[n("span",H,s(t.replaceAll("_"," "))+" : ",1),v(" "+s(a[t]),1)]))),128))])):(d(),o("div",{key:0,class:L(["bg-white rounded shadow p-2 lg:p-4 text-sm",{"animate-pulse":m.value}])},[(d(!0),o(y,null,w(Object.keys(a),t=>(d(),o("div",{class:"mt-1",key:t},[n("span",q,s(t)+" placeholder ",1)]))),128))],2))])]),footer:u(()=>[n("div",P,[n("button",{class:"btn btn-accent",onClick:V,disabled:!a.hn},s(c.confirmLabel),9,U)])]),_:1},512)]))}};export{X as default};