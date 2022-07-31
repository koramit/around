import{_ as u,r as _,g,n as f,h as w,o as b,c as y,b as n,d as l,u as s,i as h,w as x,e as v,t as V,p as k,j as I}from"./app.e42e191b.js";import{_ as F}from"./logo.06377e43.js";import{_ as p}from"./FormInput.591c2454.js";import{_ as S}from"./SpinnerButton.e1a68488.js";const c=r=>(k("data-v-cc9da15e"),r=r(),I(),r),L={class:"flex flex-col justify-center items-center w-full min-h-screen"},N=c(()=>n("div",{class:"w-40 h-40 z-10 border-primary border-4 rounded-full floating-logo"},[n("img",{src:F,alt:"around logo"})],-1)),j={class:"mt-4 px-4 py-8 w-80 bg-white rounded shadow -translate-y-20"},B=c(()=>n("span",{class:"block text-xl text-accent mt-12 text-center"},"around \u{1F932}\u{1F3FB} about \u{1F64C}\u{1F3FB} arrange",-1)),C={__name:"LoginForm",props:{layout:null,links:{type:Object,required:!0}},setup(r){const m=r,i=_(null);g(()=>{f(()=>{i.value.focus()})});const e=w({login:null,password:null,remember:!0}),d=()=>{e.transform(o=>({login:o.login.toLowerCase(),password:o.password,remember:o.remember?"on":""})).post(m.links.loginStore,{replace:!0,onFinish:()=>e.processing=!1})};return(o,a)=>(b(),y("div",L,[N,n("div",j,[B,l(p,{class:"mt-8",label:o.__("login"),name:"login",modelValue:s(e).login,"onUpdate:modelValue":a[0]||(a[0]=t=>s(e).login=t),error:s(e).errors.login,ref_key:"loginInput",ref:i},null,8,["label","modelValue","error"]),l(p,{class:"mt-2",type:"password",label:o.__("password"),name:"password",modelValue:s(e).password,"onUpdate:modelValue":a[1]||(a[1]=t=>s(e).password=t),error:s(e).errors.password,onKeydown:h(d,["enter"])},null,8,["label","modelValue","error","onKeydown"]),l(S,{spin:s(e).processing,class:"btn-accent w-full mt-8",onClick:d},{default:x(()=>[v(V(o.__("ENTER")),1)]),_:1},8,["spin"])])]))}};var $=u(C,[["__scopeId","data-v-cc9da15e"]]);export{$ as default};
