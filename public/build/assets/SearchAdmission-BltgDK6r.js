import{r as c,k as E,i as N,h as d,j,d as p,e as f,b as n,t as o,w as L,f as v,c as l,p as F,F as y,l as w,B as I,n as M}from"./app-RCW056iM.js";import{M as O}from"./ModalDialog-D900HAns.js";import{_ as T}from"./FormInput-DTViGuGV.js";import{_ as D}from"./SpinnerButton-Q4u_tiBh.js";const K={class:"font-semibold text-complement"},R={class:"py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent-darker"},$={class:"form-label block"},q={class:"bg-gray-100 text-gray-100 whitespace-nowrap"},z={key:1,class:"bg-white rounded shadow p-2 lg:p-4 text-sm"},H={class:"text-complement uppercase font-semibold"},P={class:"flex justify-end items-center"},U=["disabled"],X={__name:"SearchAdmission",props:{heading:{type:String,default:"Search Admission"},confirmLabel:{type:String,default:"CONFIRM"},mode:{type:String,default:"an"},serviceEndpoint:{type:String,required:!0}},emits:["confirmed"],setup(m,{expose:k,emit:x}){const _=m,S=x,h=c(null),b=c(null),i=c(""),r=c(""),u=c(!1),a=E({an:"",hn:"",name:"",gender:"",age:"",ward_admit:"",admitted_at:"",discharged_at:""}),C=N(()=>a.admitted_at?a.discharged_at?"latest admission":"active admission":a.hn?"patient data":null),g=()=>{u.value=!0,r.value="",a.hn="",window.axios.post(_.serviceEndpoint,{key:i.value}).then(e=>{if(!e.data.hn){r.value="Patient not found";return}(!e.data.found||e.data.discharged_at)&&(r.value="No active admission"),_.mode==="hn"&&(a.location=e.data.location),a.hn=e.data.hn,a.an=e.data.an,a.name=e.data.name,a.gender=e.data.gender,a.age=e.data.age,a.ward_admit=e.data.ward_admit,a.admitted_at=e.data.admitted_at,a.discharged_at=e.data.discharged_at}).catch(e=>{console.log(e)}).finally(()=>u.value=!1)},A=()=>{i.value="",r.value="",a.hn=""},V=()=>{h.value.open(),M(()=>b.value.focus())},B=()=>{h.value.close(),S("confirmed",{hn:a.hn,an:a.an})};return k({open:V}),(e,s)=>(d(),j(I,{to:"body"},[p(O,{ref_key:"modal",ref:h,"width-mode":"form-cols-1",onClosed:A},{header:f(()=>[n("div",K,o(m.heading),1)]),body:f(()=>[n("div",R,[p(T,{name:"an",label:m.mode,modelValue:i.value,"onUpdate:modelValue":s[0]||(s[0]=t=>i.value=t),pattern:"\\d*",type:"number",ref_key:"anInput",ref:b,error:r.value,onKeydown:L(g,["enter"])},null,8,["label","modelValue","error"]),p(D,{spin:u.value,class:"btn-complement w-full mt-2",onClick:g,disabled:!i.value.length},{default:f(()=>s[1]||(s[1]=[v(" SEARCH ")])),_:1},8,["spin","disabled"]),s[2]||(s[2]=n("hr",{class:"my-4 md:my-6"},null,-1)),n("span",$,o(C.value),1),a.hn?(d(),l("div",z,[(d(!0),l(y,null,w([...Object.keys(a)].filter(t=>a[t]),t=>(d(),l("p",{class:"mt-1 whitespace-nowrap",key:t},[n("span",H,o(t.replaceAll("_"," "))+" : ",1),v(" "+o(a[t]),1)]))),128))])):(d(),l("div",{key:0,class:F(["bg-white rounded shadow p-2 lg:p-4 text-sm",{"animate-pulse":u.value}])},[(d(!0),l(y,null,w(Object.keys(a),t=>(d(),l("div",{class:"mt-1",key:t},[n("span",q,o(t)+" placeholder ",1)]))),128))],2))])]),footer:f(()=>[n("div",P,[n("button",{class:"btn btn-accent",onClick:B,disabled:!a.hn},o(m.confirmLabel),9,U)])]),_:1},512)]))}};export{X as default};